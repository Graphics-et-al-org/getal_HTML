import Bowser from "bowser";

console.log("public_analytics.js: Loaded and running...");

/* ------------------------------------------------------------------
      1) Possibly skip analytics if ?disableAnalytics=1
      ------------------------------------------------------------------ */
let analyticsDisabled = false;

(function maybeDisableAnalytics() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get("disableAnalytics") === "1") {
        console.log("Analytics disabled (preview mode).");
        analyticsDisabled = true;
    }
})();

// browser detection
const browser = Bowser.getParser(window.navigator.userAgent);

// console.log(browser)

// console.log(browser.getPlatform())

// console.log(sessionid)



/* ------------------------------------------------------------------
      3) CORE VARIABLES
      ------------------------------------------------------------------ */
      // the se

let viewerId = "";

// visitNumber: how many times this user has visited overall
let visitNumber = 1;

// visitId: unique ID for THIS single page load (1 visit)
let visitId = "";

// flushIndex: increments each time we flush within this visit
let flushIndex = 0;

//let deviceType = browser.;
let browserName = browser.getBrowserName();
let browserVersion = browser.getBrowserVersion();

let platform =  browser.getPlatformType();
//let platformType = browser.getPlatformType();

let pageLoadTime = Date.now();

let maxScrollDepth = 0;
let scrollDepthLastBoundary = 0;
let hasUserScrolled = false;

// Snippet visibility
const visibilityMap = {};
const snippetVisibilityState = {};

// Idle logic
const IDLE_TIMEOUT_MS = 60000;
let lastActivityTime = Date.now();
let isIdle = false;
const IDLE_CHECK_INTERVAL_MS = 5000;

// Did user arrive from redirect?
let redirectSource = "no";

// Audio state
const audioState = {};

// Event queue
let eventQueue = [];

// Flush every 15 seconds
const FLUSH_INTERVAL_MS = 15000;

/* ------------------------------------------------------------------
      4) Check ?cameFromRedirect
      ------------------------------------------------------------------ */
(function checkRedirectParam() {
    if (analyticsDisabled) return; // skip if disabled
    const urlParams = new URLSearchParams(window.location.search);
    const rawVal = urlParams.get("cameFromRedirect");
    if (rawVal) {
        redirectSource = rawVal === "1" ? "yes" : rawVal;
        console.log("redirect param found => redirectSource =", redirectSource);
    }
})();

/* ------------------------------------------------------------------
      5) MAIN INIT
      ------------------------------------------------------------------ */
(function initAnalytics() {
    console.log("initAnalytics invoked. Location:", window.location.href);

    // If analytics is disabled, bail out now
    if (analyticsDisabled) {
        console.log(
            "Skipping analytics initialization because disableAnalytics=1."
        );
        return;
    }

    // 5A) sessionId from server or fallback

    // if () {
    //     const urlParams = new URLSearchParams(window.location.search);
    //     const possibleId = uuid;
    //     if (possibleId) sessionId = possibleId.trim();
    // }
    // if (!sessionId) {
    //     console.warn("No sessionId found; analytics won't store events.");
    //     return;
    // }

    // 5B) viewerId from localStorage or create new
    const LS_KEY = "myViewerId";
    viewerId = localStorage.getItem(LS_KEY);
    if (!viewerId) {
        viewerId = "viewer_" + self.crypto.randomUUID();
        localStorage.setItem(LS_KEY, viewerId);
        console.log("Generated new viewerId:", viewerId);
    } else {
        console.log("Using existing viewerId:", viewerId);
    }

    // 5C) device + browser

    // deviceType = detectDeviceType();
    // browserName = detectBrowserName();

    // 5D) visitNumber
    const visitsKey = "factSheetVisits_" + sessionid;
    const oldVisits = parseInt(localStorage.getItem(visitsKey) || "0", 10);
    visitNumber = oldVisits + 1;
    localStorage.setItem(visitsKey, String(visitNumber));

    // Generate a random visitId for THIS single page load
    visitId =
        "visit_" + Date.now() + "_" + self.crypto.randomUUID();

    flushIndex = 0;

    // 5E) attach listeners
    attachEventListeners();

    // 5F) queue page_view
    queueEvent("page_view", { visitNumber });

    // 5G) idle check
    setInterval(checkIdleState, IDLE_CHECK_INTERVAL_MS);

    // 5H) poll for Google Translate
    startGoogTransCookiePoll();

    // 5I) flush on a timer
    setInterval(() => {
        if (eventQueue.length > 0) {
            flushAnalyticsSingleDoc();
        }
    }, FLUSH_INTERVAL_MS);
})();

/* ------------------------------------------------------------------
      6) DETECT DEVICE + BROWSER
      ------------------------------------------------------------------ */
// function detectDeviceType() {
//     if (analyticsDisabled) return "disabled";
//     const ua = navigator.userAgent.toLowerCase();
//     if (/mobile|android|iphone|ipad|ipod/i.test(ua)) return "mobile";
//     return "desktop";
// }
// // Here you add or replace the existing detectBrowserName function:
// function detectBrowserName() {
//     if (analyticsDisabled) return "disabled";
//     const ua = navigator.userAgent.toLowerCase();

//     // Edge
//     if (ua.includes("edg/")) return "edge";

//     // iOS Chrome => "CriOS"
//     if (ua.includes("crios")) return "chrome-ios";

//     // Normal Chrome
//     if (ua.includes("chrome")) return "chrome";

//     // iOS Firefox => "FxiOS"
//     if (ua.includes("fxios")) return "firefox-ios";

//     // Normal Firefox
//     if (ua.includes("firefox")) return "firefox";

//     // iOS Safari or typical Safari
//     if (ua.includes("safari")) return "safari";

//     return "other";
// }

/* ------------------------------------------------------------------
      7) ATTACH LISTENERS
      ------------------------------------------------------------------ */
function attachEventListeners() {
    if (analyticsDisabled) return;

    setupGlobalIntersectionObserver();

    document.addEventListener("scroll", () => {
        hasUserScrolled = true;
        onScroll();
    });

    window.addEventListener("beforeunload", onBeforeUnload);
    window.addEventListener("error", onGlobalError);

    ["mousemove", "keydown", "click", "scroll"].forEach((evt) => {
        document.addEventListener(evt, resetActivityTimer, true);
    });

    document.addEventListener("copy", () => {
        const copiedText = window.getSelection().toString().trim();
        if (copiedText) {
            queueEvent("text_copied", { snippet: copiedText });
        }
    });

    refreshAudioListeners();
}

/* ------------------------------------------------------------------
      8) IntersectionObserver for snippets
      ------------------------------------------------------------------ */
function setupGlobalIntersectionObserver() {
    if (analyticsDisabled) return;

    const snippetElems = document.querySelectorAll(
        ".tracked-snippet, .nonclinical-snippet, [data-snippet-id]"
    );
    console.log("Found snippet-like elements:", snippetElems.length);

    snippetElems.forEach((el) => {
        if (!el.hasAttribute("data-snippet-id")) {
            const assignedId = deriveSnippetIdFromDOM(el);
            el.setAttribute("data-snippet-id", assignedId);
        }
    });

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                const idx = parseInt(
                    entry.target.getAttribute("data-elem-index"),
                    10
                );
                const snippetId =
                    entry.target.getAttribute("data-snippet-id") || "(unknown)";
                const isNowVisible = entry.intersectionRatio >= 0.8;
                const wasVisible = visibilityMap[idx] === true;

                visibilityMap[idx] = isNowVisible;

                if (!hasUserScrolled) return;

                if (isNowVisible && !wasVisible) {
                    snippetVisibilityState[idx] = {
                        startTime: Date.now(),
                        snippetId,
                    };
                } else if (!isNowVisible && wasVisible) {
                    const st = snippetVisibilityState[idx];
                    if (st) {
                        const totalSec = Math.round(
                            (Date.now() - st.startTime) / 1000
                        );
                        queueEvent("snippet_view", {
                            snippetId: st.snippetId,
                            snippetIndex: idx,
                            timeVisibleSec: totalSec,
                        });
                    }
                    delete snippetVisibilityState[idx];
                }
            });
        },
        { threshold: 0.8 }
    );

    snippetElems.forEach((el, i) => {
        el.setAttribute("data-elem-index", i);
        visibilityMap[i] = false;
        observer.observe(el);
    });
}

function deriveSnippetIdFromDOM(el) {
    const txt = el.textContent.trim().slice(0, 60);
    return `autoSnippet: ${txt}`;
}

/* ------------------------------------------------------------------
      9) SCROLL, BEFOREUNLOAD, ERRORS
      ------------------------------------------------------------------ */
function onScroll() {
    if (analyticsDisabled) return;
    resetActivityTimer();

    const docHeight = Math.max(
        document.body.scrollHeight,
        document.documentElement.scrollHeight
    );
    const scrollY = window.scrollY + window.innerHeight;
    const currentPercent = Math.round((scrollY / docHeight) * 100);

    if (currentPercent > maxScrollDepth) {
        maxScrollDepth = currentPercent;
        const boundary = Math.floor(currentPercent / 10) * 10;
        if (boundary >= scrollDepthLastBoundary + 10) {
            scrollDepthLastBoundary = boundary;
            queueEvent("scroll_depth", { scrollPercent: boundary });
        }
    }
}

function onBeforeUnload() {
    if (analyticsDisabled) return;

    const durationSec = Math.round((Date.now() - pageLoadTime) / 1000);
    queueEvent("time_on_page", { durationSec });

    flushAnalyticsSingleDoc();
}

function onGlobalError(e) {
    if (analyticsDisabled) return;
    queueEvent("js_error", {
        message: e.message,
        source: e.filename || "(inline)",
        line: e.lineno || 0,
        col: e.colno || 0,
    });
}

/* ------------------------------------------------------------------
      10) IDLE DETECTION
      ------------------------------------------------------------------ */
function resetActivityTimer() {
    lastActivityTime = Date.now();
    if (isIdle) {
        isIdle = false;
        queueEvent("user_idle_end");
    }
}

function checkIdleState() {
    if (analyticsDisabled) return;

    const now = Date.now();
    if (!isIdle && now - lastActivityTime > IDLE_TIMEOUT_MS) {
        isIdle = true;
        queueEvent("user_idle_start");
        flushAnalyticsSingleDoc();
    }
}

/* ------------------------------------------------------------------
      11) AUDIO LISTENERS
      ------------------------------------------------------------------ */
function refreshAudioListeners() {
    if (analyticsDisabled) return;

    console.log("refreshAudioListeners: scanning for <audio> elements...");
    const audioElems = document.querySelectorAll("audio");
    audioElems.forEach((audio, index) => {
        if (!audio.hasAttribute("_analyticsBound")) {
            audio.setAttribute("_analyticsBound", "1");

            if (!audioState[index]) {
                audioState[index] = {
                    lastPlayTime: null,
                    totalListened: 0,
                    duration: 0,
                    starts: 0,
                };
            }

            audio.addEventListener("loadedmetadata", () => {
                audioState[index].duration = Math.round(audio.duration);
            });

            audio.addEventListener("error", () => {
                queueEvent("audio_error", {
                    index,
                    src: audio.src || "(unknown)",
                });
            });

            audio.addEventListener("play", () => {
                const st = audioState[index];
                if (!st.lastPlayTime) {
                    st.lastPlayTime = Date.now();
                    st.starts += 1;
                }
            });

            const handleAudioStop = (reason) => {
                const st = audioState[index];
                if (st.lastPlayTime) {
                    const listenedSec = (Date.now() - st.lastPlayTime) / 1000;
                    st.totalListened += listenedSec;
                    st.lastPlayTime = null;
                }
                queueEvent("audio_session", {
                    index,
                    src: audio.src || "(unknown)",
                    totalListenedSec: Math.round(st.totalListened),
                    durationSec: st.duration,
                    starts: st.starts,
                    stopReason: reason,
                });
            };

            audio.addEventListener("pause", () => handleAudioStop("pause"));
            audio.addEventListener("ended", () => handleAudioStop("ended"));
        }
    });
}

/* ------------------------------------------------------------------
   12) queueEvent & flushAnalyticsSingleDoc
   ------------------------------------------------------------------ */

//  (A) HELPER to produce AEST date/time in dd/mmm/yyyy HH:mm:ss format
function getAESTDateTime(timestamp) {
    const d = new Date(timestamp);
    const day = d.toLocaleString("en-AU", {
        timeZone: "Australia/Sydney",
        day: "2-digit",
    });
    const mon = d.toLocaleString("en-AU", {
        timeZone: "Australia/Sydney",
        month: "short",
    });
    const year = d.toLocaleString("en-AU", {
        timeZone: "Australia/Sydney",
        year: "numeric",
    });
    const hour = d.toLocaleString("en-AU", {
        timeZone: "Australia/Sydney",
        hour: "2-digit",
        hour12: false,
    });
    const minute = d.toLocaleString("en-AU", {
        timeZone: "Australia/Sydney",
        minute: "2-digit",
    });
    const second = d.toLocaleString("en-AU", {
        timeZone: "Australia/Sydney",
        second: "2-digit",
    });
    return `${day}/${mon}/${year} ${hour}:${minute}:${second} AEST`;
}

function queueEvent(eventName, data = {}) {
    if (analyticsDisabled) return;
    // if (!db || !sessionid) {
    //     console.warn(
    //         "queueEvent called but missing db or sessionid => skipping"
    //     );
    //     return;
    // }
    const now = Date.now();
    eventQueue.push({
        t: now,
        // (B) Store an AEST date/time string for each event
        aestDateTime: getAESTDateTime(now),
        name: eventName,
        ...data,
    });
}

function flushAnalyticsSingleDoc() {
    if (analyticsDisabled) return;
    if (!eventQueue.length) return;

    flushIndex += 1;
    console.log(
        `Flushing doc => visitId=${visitId}, flushIndex=${flushIndex}, events=${eventQueue.length}`
    );

    const docPayload = {
        sessionid,
        viewerId,
        visitNumber,
        visitId,
        flushIndex,
        redirect: redirectSource,
        platform,
        browserName,
        browserVersion,
        pageLoadTime,
        events: eventQueue.slice(),
    };

    console.log('flushAnalyticsSingleDoc')
    console.log(docPayload)

    // (C) Also store a human-readable AEST date/time for this flush
   // docPayload.flushAESTDateTime = getAESTDateTime(docPayload.flushTimestamp);

    eventQueue = [];

    //function sendAnalytics(eventData) {
        fetch(`${baseurl}/api/page/${uuid}/analytics/flush`, {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${jwt}`,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(docPayload)
        });
      //}

    //@TODO flush this to a database

    // db.collection("factsheets")
    //     .doc(sessionId)
    //     .collection("metrics")
    //     .add(docPayload)
    //     .then((ref) => {
    //         console.log("Flush success; doc ID:", ref.id);
    //     })
    //     .catch((err) => {
    //         console.warn("Flush failed, re-queueing events:", err);
    //         eventQueue.push(...docPayload.events);
    //     });
}

/* ------------------------------------------------------------------
      13) Google Translate Cookie Poll
      ------------------------------------------------------------------ */
function startGoogTransCookiePoll() {
    if (analyticsDisabled) return;

    let lastVal = getGoogTransCookie();
    setInterval(() => {
        const currVal = getGoogTransCookie();
        if (currVal !== lastVal) {
            lastVal = currVal;
            const newLang = parseGoogTransCookie(currVal);
            queueEvent("translate_used", { newLang });
        }
    }, 2000);
}

function getGoogTransCookie() {
    const match = document.cookie.match(/(^|;\s?)googtrans=([^;]+)/);
    return match ? decodeURIComponent(match[2]) : "";
}

function parseGoogTransCookie(val) {
    if (!val || !val.includes("/")) return "unknown";
    const parts = val.split("/");
    return parts[parts.length - 1] || "unknown";
}

/**
 * Called externally if new snippets are inserted
 */
window.onSnippetsInserted = function () {
    if (analyticsDisabled) return;

    console.log(
        "onSnippetsInserted called. Re-running snippet & audio listeners..."
    );
    Object.keys(visibilityMap).forEach((k) => {
        visibilityMap[k] = false;
    });
    for (const k in snippetVisibilityState) {
        delete snippetVisibilityState[k];
    }

    setupGlobalIntersectionObserver();
    refreshAudioListeners();
};

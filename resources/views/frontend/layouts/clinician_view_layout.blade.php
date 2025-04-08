<!doctype html>
<html lang="{{ htmlLang() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ appName() }} | @yield('title')</title>
    <meta name="description" content="@yield('meta_description', appName())">
    <meta name="author" content="@yield('meta_author', 'Adam Landow')">
    @yield('meta')
    @stack('before-styles')
    @vite('resources/css/app.css')
    @stack('before-scripts')

</head>

<body class="bg-gray-200">


    <div class="antialiased bg-gray-50 dark:bg-gray-900">
        <header class="antialiased">
            <nav class="bg-green-200 border-gray-200 px-4 lg:px-6 py-2.5 dark:bg-gray-800 h-16 fixed w-full">
                <div class="flex flex-wrap justify-between items-center">
                    <div class="flex justify-start items-center">
                     Clinician
                      </div>
                    <div class="flex items-center lg:order-2">
                        <div class="relative">
                            {{ Auth::user()->name }}
                         </div>
                        <!-- Notifications -->

                        <!-- Dropdown menu -->

                        <button type="button" class="flex mx-3 text-sm bg-gray-800 rounded-full md:mr-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="dropdown">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-8 h-8 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="user photo">
                        </button>
                        <!-- Dropdown menu -->
                        <div class="hidden z-50 my-4 w-56 text-base list-none bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown">
                            <div class="py-3 px-4">
                                <span class="block text-sm font-semibold text-gray-900 dark:text-white"> <div class="relative">
                                    {{ Auth::user()->name }}
                                 </div></span>
                                <span class="block text-sm text-gray-500 truncate dark:text-gray-400"> {{ Auth::user()->email }}</span>
                            </div>
                            <ul class="py-1 text-gray-500 dark:text-gray-400" aria-labelledby="dropdown">

                                <li>
                                    <a href="#" class="block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-400 dark:hover:text-white">My profile</a>
                                </li>
                                <li>
                                    <a href="#" class="block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-400 dark:hover:text-white">Account settings</a>
                                </li>
                            </ul>

                            <ul class="py-1 text-gray-500 dark:text-gray-400" aria-labelledby="dropdown">
                                <li>
                                    <a href="{{ route('logout') }}" class="block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Sign out</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
          </header>

        @yield('content')
    </div>


    @yield('modals')

</body>
@stack('after-styles')
@stack('after-scripts')
@vite('resources/js/frontend/frontend.js')

</html>

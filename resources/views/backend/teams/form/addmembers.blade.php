<div class="relative p-4 w-full max-w-md max-h-full">
    <!-- Modal content -->
    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                Find members
            </h3>
            <button type="button"
                class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                data-modal-hide="addmembersdialog">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
        </div>
        <!-- Modal body -->
        <div class="p-4 md:p-5">
            <form action="{{ route('admin.teams.members.add', $team->id) }}" method="POST">
                @csrf
                <input type="hidden" name="team_id" value="{{ $team->id }}">
                <div class="col-span-full">
                    <label for="members" class="block text-sm/6 font-medium text-gray-900 w-100">Members</label>
                    <div class="mt-2 gap-x-3">
                        <select id="members" name="members[]" data-placeholder="Members" autocomplete="off" multiple required>
                            <option value="">None</option>
                        </select>
                    </div>


                </div>

                <div class="col-span-full">
                    <label for="roles" class="block text-sm/6 font-medium text-gray-900 w-100">Role(s)</label>
                    <div class="mt-2 gap-x-3">
                        <select id="roles" name="roles[]" data-placeholder="Roles" autocomplete="off" multiple required>
                            @foreach ($roles as $role)
                            <option value="{{ $role->getKey() }}" {{ in_array($role->getKey(), old('roles') ?? []) ? 'selected' : '' }}>
                                {{ $role->display_name ?? $role->name }}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="mt-2 gap-x-3">
                        <button type="submit"
                            class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add...</button>
                    </div>

                </div>
            </form>
        </div>

    </div>
</div>

{{-- <div class="row ">
    <div class="col">
        <div class="form-group">
            {{ html()->label('Zip')->for('zipfile')->attribute('style', 'color:blue; font-weight:bold') }}
            {{ html()->file('zipfile')->class('form-control') }}
        </div>
    </div>

</div>

 --}}

<div class="row">

</div>

<x-app-layout>
    <section class="main">
        <h1 class="text-2xl font-semibold mb-6">Create New User</h1>

        <form id="userForm" action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <!-- Name Field -->
                <div>
                    <x-input-label for="name" value="Name" />
                    <x-text-input id="name" name="name" type="text"
                        class="block mt-1 w-full p-2"
                        value="{{ old('name') }}" required autofocus />
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" name="email" type="email"
                        class="block mt-1 w-full p-2"
                        value="{{ old('email') }}" required />
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <x-input-label for="password" value="Password" />
                    <x-text-input id="password" name="password" type="password"
                        class="block mt-1 w-full p-2"
                        required />
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <x-input-label for="password_confirmation" value="Confirm Password" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                        class="block mt-1 w-full p-2"
                        required />
                </div>

                <!-- Role Selection -->
                <div>
                    <x-input-label for="role_id" value="Role" />
                    <select id="role_id" name="role_id" class="block mt-1 w-full p-2 border rounded">
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex w-full justify-center">
                    <x-primary-button>Save User</x-primary-button>
                </div>
            </div>
        </form>
    </section> 
</x-app-layout>

<x-app-layout>
    <section class="main">
        <h1 class="text-2xl font-semibold mb-6">Edit Post</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>⚠ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <x-input-label for="title" value="Title" />
                    <x-text-input id="title" name="title" type="text" class="block mt-1 w-full p-2 "
                        value="{{ old('title', $post->title) }}" required autofocus />
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="description" value="Description" />
                    <textarea id="description" name="description" rows="5" required>{{ old('description', $post->description) }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-3 lg:grid-cols-3 gap-6 w-full">
                    <div>
                        <x-input-label for="image" value="Image" />
                        <div class="relative">
                            <input type="file" id="image" name="image"
                                class="block  w-full opacity-0 cursor-pointer absolute inset-0" accept="image/*"
                                onchange="previewImage(event)" />

                            <label for="image"
                                class="block mt-1 w-full p-2 text-center bg-gray-100 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Choose File
                            </label>
                        </div>

                        <div class="mt-4 flex justify-center">
                            <img id="imagePreview" src="{{ $post->image ? asset('storage/' . $post->image) : '' }}"
                                alt="Image Preview"
                                class="w-32 h-32 object-cover rounded-lg {{ $post->image ? '' : 'hidden' }}">
                        </div>

                        @error('image')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <div>
                        <x-input-label for="url" value="URL" />
                        <x-text-input id="url" name="url" type="url"
                            class="block mt-1 w-full p-2 "
                            value="{{ old('url', $post->url) }}" required />
                        @error('url')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-input-label for="published_at" value="Published Date" />
                        <x-text-input id="published_at" name="published_at" type="datetime-local"
                            class="block mt-1 w-full p-2 "
                            value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}"
                            required />
                        @error('published_at')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex w-full justify-center">
                    <x-primary-button>Update Post</x-primary-button>
                </div>
            </div>
        </form>
    </section>

    <script>
        ClassicEditor
            .create(document.querySelector('#description'), {
                toolbar: [
                    'bold',
                    'italic',
                    'blockQuote',
                    'link',
                    'undo',
                    'redo',
                    '|',
                    'heading',
                   
                ],
                heading: {
                    options: [{
                            model: 'paragraph',
                            title: 'Paragraph',
                            class: 'ck-heading_paragraph'
                        },
                        {
                            model: 'heading1',
                            view: 'h1',
                            title: 'Heading 1',
                            class: 'ck-heading_heading1'
                        },
                        {
                            model: 'heading2',
                            view: 'h2',
                            title: 'Heading 2',
                            class: 'ck-heading_heading2'
                        },
                    ]
                }
            })
            .catch(error => {
                console.error(error);
            });

        function previewImage(event) {
            const imagePreview = document.getElementById('imagePreview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>

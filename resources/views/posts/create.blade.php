<x-app-layout>
    <section class="main">
        <h1 class="text-2xl font-semibold mb-6">Create New Post</h1>
        <form id="postForm" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-6">
                <div>
                    <x-input-label for="title" value="Title" />
                    <x-text-input id="title" name="title" type="text"
                        class="block mt-1 w-full p-2"
                        value="{{ old('title') }}" required autofocus />
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="description" value="Description" />
                
                    <textarea id="description" name="description" class="block mt-1 w-full" rows="5" >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="image" value="Image" />
                        <div class="relative">
                            <input type="file" id="image" name="image"
                                class="block w-full opacity-0 cursor-pointer absolute inset-0" accept="image/*"
                                onchange="previewImage(event)" />
                            <label for="image"
                                class="block mt-1 w-full p-2 text-center bg-gray-100 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Choose File
                            </label>
                        </div>
                        <div class="mt-4">
                            <img id="imagePreview" src="#" alt="Image Preview"
                                class="w-32 h-32 object-cover rounded-lg hidden">
                        </div>
                        @error('image')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-input-label for="url" value="URL" />
                        <x-text-input id="url" name="url" type="url"
                            class="block mt-1 w-full p-2"
                            value="{{ old('url') }}" required />
                        @error('url')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex w-full justify-center">
                    <x-primary-button >Save Post</x-primary-button>
                </div>
            </div>
        </form>
    </section>

    <!-- CKEditor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>

    <script>
        let editorInstance;

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
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    ]
                }
            })
            .then(editor => {
                editorInstance = editor;
            })
            .catch(error => {
                console.error(error);
            });

       
        document.getElementById('postForm').addEventListener('submit', function(e) {
            document.querySelector('#description').value = editorInstance.getData();
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

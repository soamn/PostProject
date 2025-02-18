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
                
                    <textarea id="description" name="description" >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
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
            .create(document.querySelector('#description'),{
                ckfinder: {
                uploadUrl: '{{route('posts.upload').'?_token='.csrf_token()}}',

            },
            })
            
            .then(editor => {
                editorInstance = editor;
                editor.ui.view.editable.element.style.height = "300px"; 
                
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

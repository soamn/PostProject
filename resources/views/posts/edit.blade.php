<x-app-layout>
    <section class="main">
        <h1 class="text-2xl font-semibold mb-6">Edit Post</h1>

        <!-- Display errors if any -->
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>⚠ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form id="postForm" action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
            
                <div>
                    <x-input-label for="title" value="Title" />
                    <x-text-input id="title" name="title" type="text" class="block mt-1 w-full p-2"
                        value="{{ old('title', $post->title) }}" required autofocus />
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

             
                <div>
                    <x-input-label for="description" value="Description" />
                    <textarea id="description" name="description">{{ old('description', $post->description) }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <div class="mt-4 flex justify-center">
                    <img id="imagePreview" src="{{ $post->image ? asset('storage/' . $post->image) : '' }}"
                        alt="Image Preview"
                        class="w-32 h-32 object-cover rounded-lg {{ $post->image ? '' : 'hidden' }}">
                </div>

                @error('image')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror

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
           
                <div class="relative">
                    <x-input-label for="template" value="Template" />
                    
                    <!-- Textarea for displaying content -->
                    <textarea rows="3" class="w-full p-3 border resize-none border-gray-300 rounded-md bg-gray-100 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" id="template" disabled readonly>
                        <div id="pc-{{$post->slug}}" class="post-container"></div>
                        <script src="{{ env('APP_URL') }}/js/cdn.js?id={{ $post->slug }}"></script>
                    </textarea>
                    <button 
                        id="copy-button" 
                        type="button"
                        class="absolute top-5 right-2 p-1  text-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:text-black"
                        title="Copy to clipboard"
                    >
                        <i class="fas fa-copy"></i> 
                    </button>
   
                </div>
            </div>

            <div class="mt-6 flex w-full justify-center">
                <x-primary-button>Update Post</x-primary-button>
            </div>
            </div>
        </form>
    </section>

    <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
    <script>
        let editorInstance;

     
        ClassicEditor
            .create(document.querySelector('#description'), {
                ckfinder: {
                    uploadUrl: '{{ route('posts.upload') }}?_token={{ csrf_token() }}', 
                }
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

        document.getElementById('copy-button').addEventListener('click', function(event) {
        event.preventDefault(); 

        var textarea = document.getElementById('template');
        navigator.clipboard.writeText(textarea.value).then(() => {
            
        }).catch(err => {
            alert('Failed to copy');
        });
    });
    </script>
</x-app-layout>

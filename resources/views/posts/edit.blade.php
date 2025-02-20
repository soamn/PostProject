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

                <div>
                    <x-input-label for="published_at" value="Published Date" />
                    <x-text-input id="published_at" name="published_at" type="datetime-local"
                        class="block mt-1 w-full p-2"
                        value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}"
                        required />
                    @error('published_at')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative">
                    <x-input-label for="template" value="Template" />

                    <textarea rows="3"
                        class="w-full p-3 border resize-none border-gray-300 rounded-md bg-gray-100 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        id="template" disabled readonly>
                        <div id="pc-{{ $post->slug }}" class="post-container"></div>
                        <script src="{{ env('APP_URL') }}/js/cdn.js?id={{ $post->slug }}"></script>
                    </textarea>
                    <button id="copy-button" type="button"
                        class="absolute top-5 right-2 p-1 text-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:text-black"
                        title="Copy to clipboard">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>

            <div class="mt-6 flex w-full justify-center">
                <x-primary-button>Update Post</x-primary-button>
            </div>
        </form>
    </section>

    <x-slot:scripts>
        <script>
            tinymce.init({
                selector: '#description',
                plugins: '  lists link image charmap preview anchor code fullscreen insertdatetime media table code help wordcount',
                toolbar: "undo redo| blocks | bold italic underline code | align numlist bullist | link image codesample | table  | lineheight outdent indent| forecolor backcolor removeformat | charmap emoticons | code fullscreen preview | save print | pagebreak anchor | ltr rtl",         
                height: 400,
                images_upload_handler: async (blobInfo) => {
                    try {
                        const formData = new FormData();
                        formData.append('file', blobInfo.blob(), blobInfo.filename());
                        const response = await fetch('{{ route('posts.upload') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            }
                        });
    
                        if (!response.ok) throw new Error(`Upload failed: ${response.statusText}`);
    
                        const data = await response.json();
                        if (!data.url) throw new Error('Server did not return a URL');
                        return data.url;
                    } catch (error) {
                        console.error('Image upload failed:', error);
                        throw error;
                    }
                }
            });
    
            document.getElementById('postForm').addEventListener('submit', function(e) {
                document.querySelector('#description').value = tinymce.get('description').getContent();
            });
    
            document.getElementById('copy-button').addEventListener('click', function(event) {
                event.preventDefault();
                var textarea = document.getElementById('template');
                navigator.clipboard.writeText(textarea.value).then(() => {
                    console.log('Copied to clipboard');
                }).catch(err => {
                    alert('Failed to copy');
                });
            });
        </script>
    </x-slot:scripts>
</x-app-layout>

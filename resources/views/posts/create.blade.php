<x-app-layout>
    <section class="main">
        <h1 class="text-2xl font-semibold mb-6">Create New Post</h1>
        <form id="postForm" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-6">
                <div>
                    <x-input-label for="title" value="Title (Optional)" />
                    <x-text-input id="title" name="title" type="text" class="block mt-1 w-full p-2"
                    value="{{ old('title') }}" autofocus placeholder="Enter title (Optional)" />
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>                
                <div class="pros">
                    <x-input-label for="description" value="Description" />

                    <textarea id="description" name="description">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-6 flex w-full justify-center">
                    <x-primary-button>Save Post</x-primary-button>
                </div>
            </div>
        </form>
    </section>
    <x-slot:scripts>
        
        <script>
            tinymce.init({
                selector: '#description',
                selector: '#description',
                plugins: '  lists link image charmap preview anchor code fullscreen insertdatetime media table code help wordcount',
                toolbar: "undo redo| blocks | bold italic underline code | align numlist bullist | link image codesample | table  | lineheight outdent indent| forecolor backcolor removeformat | charmap emoticons | code fullscreen preview | save print | pagebreak anchor | ltr rtl",         
                height: 400, 
                menubar: false, 
                branding: false, 
                images_upload_handler: async (blobInfo, progress) => {
                    try {
                        const formData = new FormData();
                        formData.append('file', blobInfo.blob(), blobInfo.filename());

                        const response = await fetch('/posts/upload', {
                            method: 'POST',
                            body: formData
                        });
                        console.log(response);
                        if (!response.ok) throw new Error('Upload failed');
                        const data = await response.json();
                        return data.url;
                    } catch (error) {
                        console.error('Image upload failed:', error);
                        throw error;
                    }
                }
            });
        </script>


    </x-slot:scripts>
</x-app-layout>

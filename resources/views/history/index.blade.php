<x-app-layout>
    <section class="main">
        <div class="flex items-center gap-4 justify-between ">
            <h1 class="text-2xl font-bold mb-4">Post History</h1>
            <a href="{{ route('posts.edit',$id) }}" class="text-blue-500 hover:text-blue-600">  <i class="fa-solid fa-pen-to-square"></i> Edit Current</a>
        </div>
        <div class="  ring-1 ring-black p-4 rounded-lg lg:min-w-[1000px] ">
            <table id="post-history" class=" divide-y divide-gray-200 ">
                <thead class="">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Last Updated</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                </tbody>
            </table>
        </div>
        <script>
            let postId = {{ $id }};
            $(document).ready(function() {
                $('#post-history').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: `/posts/${postId}/history`,
                    },
                    columns: [

                        {
                            data: 'old_values',
                            name: 'old_values',

                        }, {
                            data: 'created_at',
                            name: 'created_at',

                        },
                        {
                            data: 'actions',
                            name: 'actions',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    order: [[1, 'desc']],
                    responsive: true,
                    bLengthChange: true,
                    bInfo: true,
                    bAutoWidth: false
                });
            });
        </script>

</x-app-layout>

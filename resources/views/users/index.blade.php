<x-app-layout>
    <section class="main">

        <div class="flex items-center gap-4  ">
            <div class="p-4 mb-6 ring-1 ring-black rounded-lg w-fit">
                <p class="text-lg font-medium overflow-clip">Total Users: <span id="totalUsersCount">0</span></p>
            </div>
            <a href="{{ route('users.create') }}" class="p-4 mb-6 ring-1 ring-black rounded-lg w-fit">
                Create New User
            </a>
        </div>
        <div class="  ring-1 ring-black p-4 rounded-lg lg:min-w-[1000px] ">
            <table id="usersTable" class=" divide-y divide-gray-200 ">
                <thead class="">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            S.No.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">

                </tbody>
            </table>
        </div>
    </section>

    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                dom: "fltip",
                ajax: {
                    url: "{{ route('users.index') }}",
                    dataSrc: function(json) {
                        $('#totalUsersCount').text(json.recordsTotal);
                        return json.data;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
                responsive: true,
                bLengthChange: true,
                bInfo: true,
                bAutoWidth: false
            });
        });
    </script>
</x-app-layout>

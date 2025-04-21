@extends('admin.layout')

@section('main')
@php use Illuminate\Support\Str; @endphp
    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header">
                    <h2>Categories List</h2>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Add Category</a>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search categories..."
                            style="max-width: 300px;">
                    </div>
                    <table id="categoriesTable" class="table table-hover table-category" style="width:100%">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>ID</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td class="py-0">
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="Category Image"
                                            width="50">
                                    </td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->id }}</td>
                                    <td>{{ Str::limit($category->description, 250, '...') ?? 'No description available' }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="dropdown-toggle btn btn-sm btn-light" href="#" role="button"
                                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                               <i class="mdi mdi-dots-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route('admin.categories.edit', $category->id) }}"
                                                   class="dropdown-item">
                                                   <i class="mdi mdi-pencil mr-2"></i> Edit
                                                </a>
                                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"
                                                            onclick="return confirm('Are you sure?')">
                                                        <i class="mdi mdi-delete mr-2"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#categoriesTable tbody tr');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(
                    searchValue));
                row.style.display = match ? '' : 'none';
            });
        });
    </script>
@endsection

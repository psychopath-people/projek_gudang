@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Products List</h2>
                    <a class="btn btn-success" href="{{ route('products.create') }}">Create New Product</a>
                </div>
            </div>
        </div>

        <!-- Search Form -->
        <div class="row mb-3">
            <div class="col-lg-12">
                <form action="{{ route('products.index') }}" method="GET" class="d-flex">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search"
                            placeholder="Search by name, description or price..." value="{{ $search ?? '' }}">
                        <button class="btn btn-primary" type="submit">Search</button>
                        @if ($search)
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Clear</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p class="mb-0">{{ $message }}</p>
            </div>
        @endif

        @if ($products->isEmpty())
            <div class="alert alert-info">
                No products found.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th width="280px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->description }}</td>
                                <td>{{ number_format($product->price, 2) }}</td>
                                <td>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                        class="d-inline">
                                        <a class="btn btn-primary btn-sm"
                                            href="{{ route('products.edit', $product->id) }}">Edit</a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <!-- Optional: Add these styles to your layout or create a new CSS file -->
    <style>
        .pagination {
            margin: 0;
            padding: 0;
        }

        .pagination li {
            margin: 0 2px;
        }

        .pagination li a,
        .pagination li span {
            border-radius: 3px;
            padding: 6px 12px;
        }
    </style>
@endsection

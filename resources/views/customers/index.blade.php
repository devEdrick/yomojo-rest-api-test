@extends('layouts.app')

@section('title', 'Customer List')

@section('content')
    <h1>Customer List</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Age</th>
                <th>Date of Birth</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
                <tr>
                    <td>{{ $customer['id'] }}</td>
                    <td>{{ $customer['first_name'] }}</td>
                    <td>{{ $customer['last_name'] }}</td>
                    <td>{{ $customer['age'] }}</td>
                    <td>{{ $customer['dob'] }}</td>
                    <td>{{ $customer['email'] }}</td>
                    <td>
                        <a href="{{ route('customers.edit', $customer['id']) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('customers.destroy', $customer['id']) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('customers.create') }}" class="btn btn-primary">Create Customer</a>
@endsection

@extends('layouts.app')

@section('content')
<div class="row d-flex justify-content-center">
  <div class="col-sm-5">
    <div class="card">
      <div class="card-body">
        <h3>Create product</h3>
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @include('products._form', ['product' => $product])
          <div class="row">
            <button type="submit" class="btn btn-primary">Crear producto</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
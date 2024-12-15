@extends('layouts.app')

@section('content')
<div class="row d-flex justify-content-center">
  <div class="col-sm-5">
    <div class="card">
      <div class="card-body">
        <h3>Edit product</h3>
        <form action="{{ route('products.update',['product' => $product]) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PATCH')
          @include('products._form', ['product' => $product])
        <div class="row">
            <button type="submit" class="btn btn-primary">Editar producto</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
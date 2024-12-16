<div class="col-sm-4">
  <div class="card m-3" style="width: 18rem;">
    @if (is_null(optional($product)->image))
    <img class="card-img-top" src="https://placehold.co/600x400" alt="Card image cap">
    @else
    <img class="card-img-top" src="{{ asset($product->image) }}" alt="Card image cap" style="width: 286px; height: 190px;">
    @endif
    <div class="card-body">
      <h5 class="card-title">{{ $product->name }}</h5>
      <p class="card-text">{{ str($product->description)->limit(40) }} ..</p>
      <div class="row">
        <div class="btn-group d-flex justify-content-end" role="group" arial-label="Basic example">
          <button data-id="{{ $product->id}}" class="addToCart btn btn-primary">Add</button>
          @if (auth()->user()->isAdmin())
            <a href="{{ route('products.edit', [ 'product' => $product]) }}" class="btn btn-warning">Editar</a>
            <form action="{{ route('products.destroy',[ 'product' => $product]) }}" method="POST" onsubmit="return confirmDelete();">
              @method('delete')
              @csrf
              <button type="submit" class="btn btn-danger">Eliminar</button>
            </form>
          @endif
        </div>
      </div>      
    </div>
  </div>
</div>
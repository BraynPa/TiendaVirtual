<div class="col-sm-4">
  <div class="card m-3" style="width: 18rem;">
    @if (is_null(optional($product)->image))
    <img class="card-img-top" src="https://placehold.co/600x400" alt="Card image cap">
    @else
    <img class="card-img-top" src="{{ asset($product->image) }}" alt="Card image cap" style="width: 286px; height: 190px;">
    @endif
    <div class="card-body">
      <h5 class="card-title">
        {{ $product->name }}
      </h5>
      <p class="card-text">{{ str($product->description)->limit(20) }} ..</p>
      <form id="deleteProductForm"
        action="{{ route('products.destroy', ['product' => $product]) }}"
        method="POST"
        onsubmit="return confirmDelete();">
        @csrf
        @method('DELETE')
      </form>
      <div class="row">
        <div class="btn-group d-flex justify-content-end" role="group" aria-label="Basic example">
          <button wire:click="addToCart({{ $product }})" class="btn btn-primary">Add</button>
          @if (auth()->user()->isAdmin())
            <a href="{{ route('products.edit', ['product' => $product]) }}" class="btn btn-warning">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-down-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M9.636 2.5a.5.5 0 0 0-.5-.5H2.5A1.5 1.5 0 0 0 1 3.5v10A1.5 1.5 0 0 0 2.5 15h10a1.5 1.5 0 0 0 1.5-1.5V6.864a.5.5 0 0 0-1 0V13.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5" />
                <path fill-rule="evenodd" d="M5 10.5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 0-1H6.707l8.147-8.146a.5.5 0 0 0-.708-.708L6 9.293V5.5a.5.5 0 0 0-1 0z" />
              </svg>
            </a>

            <button type="submit" form="deleteProductForm" class="btn btn-danger">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
              </svg>
            </button>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
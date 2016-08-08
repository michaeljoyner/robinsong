<div class="modal fade rs-form-modal" id="product-clone-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Clone this Product</h4>
            </div>
            <div class="modal-body">
                <p>This will make a copy of the "{{ $product->name }}" and put it into the same category. The product's pricing and customisation options will also be copied, but not the images or tags.</p>
                {!! Form::open(['url' => '/admin/products/' . $product->id . '/clones', 'class' => 'rs-form']) !!}
                <div class="form-group">
                    <label for="name">Name: </label>
                    {!! Form::text('name', null, ['class' => "form-control"]) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn rs-btn btn-light" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn rs-btn">Make copy</button>
                {!! Form::close() !!}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
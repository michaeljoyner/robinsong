<div class="modal fade rs-form-modal" id="stockunit-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add a new shipping unit</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['url' => $formAction, 'class' => 'rs-form form-horizontal']) !!}
                @include('errors')
                <div class="form-group">
                    <label for="name">Name: </label>
                    {!! Form::text('name', null, ['class' => "form-control"]) !!}
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="price">Price: </label>
                            <div class="input-group">
                                <span class="input-group-addon">&pound;</span>
                                {!! Form::text('price', null, ['class' => "form-control", 'required' => 'true']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-5">
                        <div class="form-group">
                            <label for="weight">Weight: </label>
                            <div class="input-group">
                                {!! Form::text('weight', null, ['class' => "form-control", 'required' => 'true']) !!}
                                <span class="input-group-addon">g</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn rs-btn btn-light" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn rs-btn">Create</button>
                {!! Form::close() !!}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
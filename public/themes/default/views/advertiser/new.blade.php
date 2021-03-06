@extends('layout.fixedtwo')

@section('left')
        <h5>Account Information</h5>

        {{ Former::text('username','Username') }}
        {{ Former::text('email','Email') }}

        {{ Former::password('pass','Password') }}
        {{ Former::password('repass','Repeat Password') }}

        <h5>Owner / Personal Info</h5>

        {{ Former::text('fullname','Full Name') }}

        <div class="row">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('phone','Phone') }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('mobile','Mobile 1') }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('mobile1','Mobile 2') }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('mobile2','Mobile 3') }}
            </div>
        </div>


        {{ Former::text('street','Address') }}

        <div class="row">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('district','Kecamatan') }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('city','City') }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('zip','ZIP / Kode Pos') }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('province','Province') }}
            </div>
        </div>

        {{ Former::select('country')->id('country')->options(Config::get('country.countries'))->label('Country of Origin') }}

        {{ Former::select('status')->options(array('inactive'=>'Inactive','active'=>'Active'))->label('Status') }}

        {{ Form::submit('Save',array('class'=>'btn btn-primary'))}}&nbsp;&nbsp;
        {{ HTML::link($back,'Cancel',array('class'=>'btn'))}}

@stop

@section('right')
        <h5>Shop Information</h5>

        <h5>Logo Image</h5>
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                {{ Former::select('useImage')->label('Image Source')->options(array('linked'=>'Linked Image','upload'=>'Uploaded Image'))->label('Choose whether to use linked of uploaded image') }}
            </div>
        </div>
        <h6>Link to Image</h6>
        {{ Former::text('extImageURL','External Logo URL')->help('Image URL eg: "http://some.domain/image.jpg"') }}

        <h6>Upload Image</h6>
        <?php
            $fupload = new Fupload();
        ?>
        {{ $fupload->id('imageupload')->title('Select Logo Picture')->label('Upload Picture')
            ->url('upload/logo')
            ->singlefile(true)
            ->prefix('asset')
            ->multi(false)->make() }}

        {{ Former::text('merchantname','Shop Name')->class('form-control') }}

        {{ Former::text('mc_url','Website URL')->class('form-control') }}

        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                {{ Former::text('bank','Bank')->class('form-control') }}
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                {{ Former::text('account_number','Account Number')->class('form-control') }}
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                {{ Former::text('account_name','Account Name')->class('form-control') }}
            </div>
        </div>

        {{ Former::text('mc_street','Address')->class('form-control') }}

        <div class="row">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('mc_district','Kecamatan') }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('mc_city','City') }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('mc_zip','ZIP / Kode Pos') }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('mc_province','Province') }}
            </div>
        </div>

        {{ Former::select('mc_country')->id('mc_country')->options(Config::get('country.countries'))->label('Country of Origin') }}

        <div class="row">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('mc_phone','Phone') }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('mc_mobile','Mobile 1') }}
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                {{ Former::text('mc_email','Shop Email')->class('form-control') }}
            </div>
        </div>

        {{ Former::text('tags','Tags / Keywords')->class('tag_keyword') }}

        {{ Former::textarea('shopDescription','Shop Description or Promotion')->id('descEditor')->class('editor')->rows(10)->columns(20) }}




        <h6>Upload Cover Image</h6>
        <?php
            $fupload = new Fupload();
        ?>
        {{ $fupload->id('imageupload')->title('Select Picture')->label('Upload Picture')
            ->url('upload/asset')
            ->singlefile(true)
            ->prefix('asset')
            ->multi(false)->make() }}


@stop

@section('modals')

@stop

@section('aux')

{{ HTML::script('ckeditor_full/ckeditor.js') }}


<script type="text/javascript">

$(document).ready(function() {


    $('.pick-a-color').pickAColor();

    $('#name').keyup(function(){
        var title = $('#name').val();
        var slug = string_to_slug(title);
        $('#permalink').val(slug);
    });

    CKEDITOR.replace( 'descEditor' );

    $('#location').on('change',function(){
        var location = $('#location').val();
        console.log(location);

        $.post('{{ URL::to('asset/rack' ) }}',
            {
                loc : location
            },
            function(data){
                var opt = updateselector(data.html);
                $('#rack').html(opt);
            },'json'
        );

    })

    $('.auto_merchant').autocomplete({
        source: base + 'ajax/merchant',
        select: function(event, ui){
            $('#merchant-id').val(ui.item.id);
        }
    });

    function updateselector(data){
        var opt = '';
        for(var k in data){
            opt += '<option value="' + k + '">' + data[k] +'</option>';
        }
        return opt;
    }


});

</script>

@stop
@if (isset($$module_name_singular))
    <input type="hidden" name="draft_parent" value="{{$$module_name_singular->draft_parent}}" >
    <input type="hidden" name="status" value="{{$$module_name_singular->status}}" >
@else
    <input type="hidden" name="draft_parent" value="" >
    <input type="hidden" name="status" value="" >
@endif

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <?php
            $field_name = 'name';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>

    <div class="col-6">
        <div class="form-group">
            <?php
            $field_name = 'slug';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>
</div>


<div class="row">
    <div class="col-4">
        <div class="form-group">
            <?php
            $field_name = 'type';
            $field_lable = label_case($field_name);
            $field_placeholder = "-- Select an option --";
            $required = "required";
            $select_options = [
                'home' => 'Homepage',
                'content'=>'Content',
                'bingo'=>'Bingo Page',
                'slots'=>'Slots Page',
                'welcome-offer'=>'Welcome Offer',
                'quiz'=>'Quiz Page',
                'promotions' => 'Promotions Page',
                'legal'=>'Legal Page',
                'error'=>'Error Page',
            ];
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>
    <div class="col-4">
        <div class="form-group">
            <?php
            $field_name = 'parent';
            $field_lable = label_case($field_name);
            $field_placeholder = "-- Select an option --";
            $field_relation = "get_parent";
            $required = "";
            
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->select($field_name, isset($$module_name_singular)?optional($$module_name_singular->$field_relation)->pluck('name', 'id'):'')->placeholder($field_placeholder)->class('form-control select2-parent')->attributes(["$required"]) }}
            {{-- {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }} --}}
        </div>
    </div>
    <div class="col-4">
        <div class="form-group pt-4">
            <?php
            $field_name = 'show_in_menu';
            $field_lable = 'Show in Main Menu';
            $field_placeholder = $field_lable;
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->checkbox($field_name)->placeholder($field_placeholder)->class('')->attributes(["$required"]) }}
        </div>
    </div>
</div>

<div class="row">    
    <div class="col-12">
        <div class="form-group">
            <?php
            $field_name = 'description';
            $field_lable = 'Banner Text';
            $field_placeholder = $field_lable;
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{-- <textarea class="form-control my-editor" name="description" id="description"></textarea> --}}
            {{ html()->textarea($field_name)->placeholder($field_placeholder)->class('form-control my-editor')->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">    
    <div class="col-12">
        <div class="form-group">
            <?php
            $field_name = 'offer_text';
            $field_lable = 'Terms Text';
            $field_placeholder = $field_lable;
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->textarea($field_name)->placeholder($field_placeholder)->class('form-control my-editor')->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group">
            <?php
            $field_name = 'content';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->textarea($field_name)->placeholder($field_placeholder)->class('form-control my-editor')->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group">
            <?php
            $field_name = 'banner_image';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = "";
            ?>
            {!! Form::label("$field_name", "$field_lable") !!} {!! fielf_required($required) !!}
            <div class="input-group mb-3">
                {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required", 'aria-label'=>'Image', 'aria-describedby'=>'button-image']) }}
                <div class="input-group-append">
                    <button class="btn btn-info" type="button" id="banner_image_button"><i class="fas fa-folder-open"></i> Browse</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group">
            <?php
            $field_name = 'banner_image_mob';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = "";
            ?>
            {!! Form::label("$field_name", "$field_lable") !!} {!! fielf_required($required) !!}
            <div class="input-group mb-3">
                {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required", 'aria-label'=>'Image', 'aria-describedby'=>'button-image']) }}
                <div class="input-group-append">
                    <button class="btn btn-info" type="button" id="banner_image_mob_button"><i class="fas fa-folder-open"></i> Browse</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-5">
        <div class="form-group">
            <?php
            $field_name = 'meta_title';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>
    <div class="col-5">
        <div class="form-group">
            <?php
            $field_name = 'meta_keywords';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-6">
        <div class="form-group">
            <?php
            $field_name = 'meta_description';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>
    <div class="col-12 col-sm-6">
        <div class="form-group">
            <?php
            $field_name = 'meta_og_image';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group">
            <?php
            $field_name = 'meta_og_url';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div></div>

@push('after-styles')

<!-- Select2 Bootstrap 4 Core UI -->
<link href="{{ asset('vendor/select2/select2-coreui-bootstrap4.min.css') }}" rel="stylesheet" />

<!-- Date Time Picker -->
<link rel="stylesheet" href="{{ asset('vendor/bootstrap-4-datetime-picker/css/tempusdominus-bootstrap-4.min.css') }}" />

<!-- File Manager -->
<link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
@endpush

@push ('after-scripts')
<!-- Select2 Bootstrap 4 Core UI -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    $('.select2-parent').select2({
        theme: "bootstrap",
        placeholder: "-- Select an option --",
        minimumInputLength: 2,
        allowClear: true,
        ajax: {
            url: '{{route("backend.pages.index_list")}}',
            dataType: 'json',
            data: function (params) {
                return {
                    q: $.trim(params.term)
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
});
</script>
{{-- <script src="https://cdn.tiny.cloud/1/7pplelrerpj7orr9hck3zqd4wlk433lydajp2e63sw7qvaf7/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> --}}

<script src="https://cdn.tinymce.com/4/tinymce.min.js"></script>
<script src={{asset('js/tinymce.js')}}></script>

<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function() {

        document.getElementById('banner_image_button').addEventListener('click', (event) => {
                event.preventDefault();
                inputId = 'banner_image';
                window.open('/file-manager/fm-button', 'fm', 'width=1200,height=1000');
        });
        // second button
        document.getElementById('banner_image_mob_button').addEventListener('click', (event) => {
                event.preventDefault();
                inputId = 'banner_image_mob';
                window.open('/file-manager/fm-button', 'fm', 'width=1200,height=1000');
        });
    });

    let inputId = '';
    let inputIdImg = '';

    // set file link
    function fmSetLink($url) {
        document.getElementById(inputId).value = $url
    }
</script>
@endpush

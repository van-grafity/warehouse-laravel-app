@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')

<div class="row justify-content-center">
    <div class="col-sm-12 col-md-6 col-xl-4">
        <!-- Profile Image -->
        <div class="card card-teal card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle" src="{{ asset('images/user-profile-default.png') }}" alt="User profile picture">
            </div>

            <h3 class="profile-username text-center">{{ $user->name }}</h3>

            <p class="text-muted text-center d-none">{{ $user->role }}</p>

            <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                <b>Email</b> : {{ $user->email }}
                </li>
                <li class="list-group-item">
                <b>Department</b> : {{ $user->department->department}}
                </li>
                <li class="list-group-item">
                <b>Roles</b> : {{ $user->role}}
                </li>
            </ul>

            <a href="javascript:void(0);" class="btn bg-teal btn-block" onclick="show_modal_change_password('modal_change_password')">
                Change Password <i class="fas fa-key"></i>
            </a>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>


<!-- Modal Add and Edit User Profile -->
<div class="modal fade" id="modal_change_password" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post" onsubmit="stopFormSubmission(event)">

            <!-- <form action="" method="post"> -->
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="old_password">Old Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Enter Old Password">
                            <div class="input-group-append">
                                <span class="input-group-text toggle-password">
                                    <i class="fa fa-eye-slash"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter New Password">
                            <div class="input-group-append">
                                <span class="input-group-text toggle-password">
                                    <i class="fa fa-eye-slash"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="new_password_confirm">Confirm New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password_confirm" name="new_password_confirm" placeholder="Enter New Confirm Password">
                            <div class="input-group-append">
                                <span class="input-group-text toggle-password">
                                    <i class="fa fa-eye-slash"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- <button type="submit" class="btn btn-primary btn-submit" onclick="submitForm('modal_change_password')">Save</button> -->
                    <button type="submit" class="btn btn-primary btn-submit" id="btn_submit_form">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Add and Edit User Profile -->

@endsection

@section('js')
<script type="text/javascript">
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const change_password_url = '{{ route("profile.change-password") }}';

</script>
<script type="text/javascript">

    const show_modal_change_password = (modal_element_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Change Password",
            btn_submit : "Save",
            form_action_url : change_password_url,
        }
        clear_form(modal_data);
        $(`#${modal_element_id}`).modal('show');
    }

</script>
<script type="text/javascript">
    $(".toggle-password").click(function() {
        $(this).find("i").toggleClass("fa-eye fa-eye-slash");
        let input = $($(this).parent().prev("input"));
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
</script>

<script type="text/javascript">
    // ## Form Validation
    let rules = {
        old_password: {
            required: true,
        },
        new_password: {
            required: true,
        },
        new_password_confirm: {
            required: true,
            equalTo: "#new_password"
        }
    };
    let messages = {
        old_password: {
            required: "Please enter the Password",
        },
        new_password: {
            required: "Please enter your New Password",
        },
        new_password_confirm: {
            required: "Please enter your New Password Confirmation",
            equalTo: "Must be same as your New Password"
            
        },
    };
    let validator = $("#modal_change_password form").validate({
        rules: rules,
        messages: messages,
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
        submitHandler: function (form) {
            $('#btn_submit_form').attr('disabled',true);
            
            let formData = getFormData(this.currentForm);
            fetch_data = {
                url: change_password_url,
                method: "POST",
                data: formData,
                token: token,
            }

            using_fetch(fetch_data).then((result) => {
                if(result.status == "success"){
                    swal_info({ title : result.message });
                    $('#modal_change_password').modal('hide');
                    $('#btn_submit_form').attr('disabled', false);
                } else {
                    swal_warning({ title: result.message });
                    $('#btn_submit_form').attr('disabled', false);
                }
            }).catch((err) => {
                swal_failed({ title: err });
                $('#btn_submit_form').attr('disabled', false);
            });
        }
    });
</script>

@stop
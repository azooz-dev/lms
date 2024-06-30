@extends('admin.dashboard')

@section('content')


<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Role In Permissions</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="card">
        <div class="card-body p-4">
            <form action="{{ route('admin.update_role_permissions', $role->id) }}" method="POST" id="myForm" class="row g-3" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label for="input1" class="form-label">Roles Name</label>
                    <h4>{{ $role->name }}</h4>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="allPermission" onclick="checkAllPermissions(this)">
                    <label class="form-check-label" for="allPermission">All Permissions</label>
                </div>

                <hr>

                @foreach ($permissionGroups as $key => $permissionGroup)
                <div class="row">
                    <div class="col-3">
                            <div class="form-check">
                                <input class="form-check-input permission-group-checkbox" type="checkbox" value="" id="{{ $permissionGroup }}" onclick="checkGroupPermissions(this)">
                                <label class="form-check-label" for="{{ $permissionGroup }}">{{ $permissionGroup }}</label>
                            </div>
                        </div>
                        <div class="col-9">
                            @foreach ($permissions as $key => $permission)
                            @if ($permission->group_name === $permissionGroup)
                                <div class="form-check">
                                    <input class="form-check-input permission-checkbox" type="checkbox" {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }} name="permission[]" value="{{ $permission->name }}" id="{{ $key }}" data-group="{{ $permissionGroup }}" onclick="checkIndividualPermission(this)">
                                    <label class="form-check-label" style="text-transform: capitalize" for="{{ $key }}">{{ $permission->name }}</label>
                                </div>
                            @endif
                            @endforeach
                            <br>
                        </div>
                    </div>
                    @endforeach

                    
                <div class="col-md-6" >
                    <div class="d-md-flex d-grid align-items-center gap-3" style="margin: 0;">
                        <button type="submit" style="margin: 0;" class="btn btn-primary px-4">Save</button>
                    </div>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>

<script>
    
    /**
     * Initializes the checkboxes based on the initial state of the permission checkboxes.
     */
    function initializeCheckboxes() {
        // Select all permission checkboxes
        let allCheckboxes = document.querySelectorAll('.permission-checkbox');
        let allChecked = true;

        // Iterate through each permission checkbox
        allCheckboxes.forEach((checkbox) => {
            // If any checkbox is not checked, set allChecked to false
            if (!checkbox.checked) {
                allChecked = false;
            }

            // Update the group checkbox based on the state of the permission checkboxes in the same group
            let groupCheckbox = document.querySelector('.permission-group-checkbox[id="' + checkbox.dataset.group + '"]');
            let groupCheckboxes = document.querySelectorAll('.permission-checkbox[data-group="' + checkbox.dataset.group + '"]');
            let allInGroupChecked = Array.from(groupCheckboxes).every(cb => cb.checked);
            if (groupCheckbox) {
                groupCheckbox.checked = allInGroupChecked;
            }
        });

        // Update the "All Permissions" checkbox based on the state of all permission checkboxes
        let allCheckbox = document.getElementById('allPermission');
        if (allCheckbox) {
            allCheckbox.checked = allChecked;
        }
    }

    /**
     * Checks all permission checkboxes when the "All Permissions" checkbox is checked.
     * Unchecks all permission checkboxes when the "All Permissions" checkbox is unchecked.
     *
     * @param {HTMLInputElement} element - The checkbox element that triggered the function.
     */
    function checkAllPermissions(element) {
        let checkboxes = document.querySelectorAll('.permission-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = element.checked;
        });

        let groupCheckboxes = document.querySelectorAll('.permission-group-checkbox');
        groupCheckboxes.forEach(checkbox => {
            checkbox.checked = element.checked;
        });
    }

    /**
     * Checks all permission checkboxes in a group when the group checkbox is checked.
     * Unchecks all permission checkboxes in a group when the group checkbox is unchecked.
     *
     * @param {HTMLInputElement} element - The checkbox element that triggered the function.
     */
    function checkGroupPermissions(element) {
        let checkboxes = document.querySelectorAll('.permission-checkbox[data-group="' + element.id + '"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = element.checked;
        });
    }

    /**
     * Updates the group checkbox and "All Permissions" checkbox based on the state of the individual permission checkbox.
     *
     * @param {HTMLInputElement} element - The checkbox element that triggered the function.
     */
    function checkIndividualPermission(element) {
        let groupCheckbox = document.querySelector('.permission-group-checkbox[id="' + element.dataset.group + '"]');
        let allCheckbox = document.getElementById('allPermission');
        let groupCheckboxes = document.querySelectorAll('.permission-checkbox[data-group="' + element.dataset.group + '"]');
        let allCheckboxes = document.querySelectorAll('.permission-checkbox');

        // Check if all checkboxes in the group are checked
        let allInGroupChecked = Array.from(groupCheckboxes).every(checkbox => checkbox.checked);
        groupCheckbox.checked = allInGroupChecked;

        // Check if all checkboxes are checked
        let allChecked = Array.from(allCheckboxes).every(checkbox => checkbox.checked);
        allCheckbox.checked = allChecked;
    }

    /**
     * Updates the group checkbox and "All Permissions" checkbox based on the state of the individual permission checkbox.
     *
     * @param {HTMLInputElement} element - The checkbox element that triggered the function.
     */
    function checkIndividualPermission(element) {
        let groupCheckbox = document.querySelector('.permission-group-checkbox[id="' + element.dataset.group + '"]');
        let allCheckbox = document.getElementById('allPermission');
        let groupCheckboxes = document.querySelectorAll('.permission-checkbox[data-group="' + element.dataset.group + '"]');
        let allCheckboxes = document.querySelectorAll('.permission-checkbox');

        // Determine if the current checkbox being clicked is checked
        let isChecked = element.checked;

        // Find the index of the current checkbox in its group
        let currentIndex = Array.from(groupCheckboxes).indexOf(element);

        // Check if any checkbox in the group is checked except the current one
        let groupCheckboxChecked = Array.from(groupCheckboxes)
            .filter(cb => cb!== element && cb.checked)
            .length > 0;

        // Explicitly check if the current checkbox is the last one in its group
        let isLastCheckbox = currentIndex === groupCheckboxes.length - 1;

        // Debugging statement to log relevant variables
        console.log(`Is Checked: ${isChecked}, Current Index: ${currentIndex}, Is Last Checkbox: ${isLastCheckbox}, Group Checked: ${groupCheckboxChecked}`);

        // Adjusted logic to ensure the group checkbox is checked if the last checkbox in the group is checked
        groupCheckbox.checked = isLastCheckbox && isChecked;

        // Update the "All Permissions" checkbox based on the state of all permission checkboxes
        let allCheckboxesChecked = Array.from(allCheckboxes).every(checkbox => checkbox.checked);
        allCheckbox.checked = allCheckboxesChecked;
    }

    // Call the function when the page loads
    window.addEventListener('DOMContentLoaded', (event) => {
        initializeCheckboxes();
    });
</script>
@endsection
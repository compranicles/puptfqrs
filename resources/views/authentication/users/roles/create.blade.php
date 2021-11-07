<x-app-layout>
    @include('admin.auth.header')
    <div class="container">
      <div class="row">

        <div class="col-md-12">
          @include('admin.auth.roles.components.breadcrumb')
              <li class="breadcrumb-item" aria-current="page">List</li>
            </ol>
          </nav>
          <br>
        </div>

        <div class="col-md-3">
          @include('admin.auth.index')
        </div>

        <div class="col-md-9">
          <div class="d-flex align-content-center">
            <h2 class="ml-3 mr-3">Roles</h2>
            <p class="mt-2 mr-3">Add Role.</p>
            <p class="mt-2">
              <a class="back_link" href="{{ route('roles.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Roles</a>
            </p>
          </div>

          <div class="col-md-9">
            @if ($message = Session::get('add_role_success'))
              <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </symbol>
              </svg>
              <div class="alert alert-success d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                <div class="ml-2">
                  {{ $message }}
                </div>
              </div>            
            @endif 

            <div class="card">
              <div class="card-body">
                <form method="POST" action="{{ route('roles.store') }}">
                  @csrf
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <x-jet-label value="{{ __('Name') }}" />
    
                        <x-jet-input class="{{ $errors->has('role_name') ? 'is-invalid' : '' }}" type="text" name="role_name" multiple
                                    :value="old('role_name')" required autofocus autocomplete="role_name" />
                        <x-jet-input-error for="role_name"></x-jet-input-error>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-12">
                        <label>Permissions</label>
                      </div>
                    </div>
                    <div class="row">
                    @foreach ($permissions as $permission)
                      <div class="col-md-4 ml-3">
                        <label for="{{ $permission->id }}">
                          <input type="checkbox" id="{{ $permission->id }}" value="{{ $permission->id }}" name="permissions[]">
                          {{ $permission->name }}
                          @empty
                          <p></p>
                        </label>
                      </div>
                    @endforeach
                    </div>
                  </div>       
              </div>
            </div>
            <div class="row">
              <div class="mb-0 mt-3 ml-2">
                <div class="d-flex justify-content-start align-items-baseline">
                  <button type="submit" class="btn btn-success mr-3"><i class="bi bi-save mr-2"></i>Save</button>
                  <a href="{{ route('roles.index') }}" class="btn btn-light" tabindex="-1" role="button" aria-disabled="true"><i class="bi bi-x-circle mr-2"></i>Cancel</a>
                </div>
              </div>
            </div>
          </form>
        </div>

      </div>
    </div>
    @push('scripts')
      <script>
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 4000);
      </script>
    @endpush

    @push('pagetitle', "Add Role")
  </x-app-layout>

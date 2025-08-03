<div class="tab-pane active" id="tab-home">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-solid">
                <div class="box-body">
                    <div class="row">
                        {{-- Tên --}}
                        <div class="col-sm-6">
                            <div class="form-group clearfix">
                                <label class="control-label">Tên: <strong class="red">*</strong></label>
                                <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}"
                                       class="form-control" placeholder="Tên người dùng" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Email --}}
                        <div class="col-sm-6">
                            <div class="form-group clearfix">
                                <label class="control-label">Email: <strong class="red">*</strong></label>
                                <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}"
                                       class="form-control" placeholder="Email" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Mật khẩu --}}
                        <div class="col-sm-6">
                            <div class="form-group clearfix">
                                <label class="control-label">Mật khẩu: <strong class="red">*</strong></label>
                                <input type="password" name="password" class="form-control" placeholder="Mật khẩu" {{ isset($user) ? '' : 'required' }}>
                                @if (isset($user))
                                    <small class="text-muted">Để trống nếu không đổi mật khẩu</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Ảnh đại diện --}}
                        <div class="col-sm-6">
                            <div class="form-group clearfix">
                                <label class="control-label">Ảnh đại diện:</label>
                                <input type="file" name="avatar" class="form-control">
                                @if (!empty($user->avatar))
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" style="width: 64px; height: 64px; object-fit: cover;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group clearfix">
                                <label class="control-label">Trạng thái: <strong class="red">*</strong></label>
                                <select name="is_active" class="form-control">
                                    <option value="1" {{ (isset($user) && $user->is_active == 1) ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="0" {{ (isset($user) && $user->is_active == 0) ? 'selected' : '' }}>Bị khóa</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>

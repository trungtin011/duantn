# User Avatar Component

Component này được sử dụng để hiển thị hình ảnh đại diện của người dùng một cách nhất quán trong toàn bộ ứng dụng.

## Cách sử dụng

### Cơ bản
```blade
@include('partials.user-avatar')
```

### Với các tùy chọn
```blade
@include('partials.user-avatar', [
    'user' => $user,           // User object (mặc định: auth()->user())
    'size' => 'md',            // Kích thước: xs, sm, md, lg, xl, 2xl
    'showName' => false,       // Hiển thị tên người dùng
    'className' => ''          // CSS class bổ sung
])
```

## Các kích thước có sẵn

- `xs`: 16x16px
- `sm`: 24x24px  
- `md`: 32x32px (mặc định)
- `lg`: 48x48px
- `xl`: 64x64px
- `2xl`: 80x80px

## Ví dụ sử dụng

### Avatar nhỏ trong header
```blade
@include('partials.user-avatar', ['size' => 'sm'])
```

### Avatar lớn với tên người dùng
```blade
@include('partials.user-avatar', [
    'user' => $user,
    'size' => 'lg',
    'showName' => true
])
```

### Avatar trong profile
```blade
@include('partials.user-avatar', [
    'user' => $user,
    'size' => '2xl'
])
```

## Tính năng

1. **Fallback tự động**: Nếu không có avatar, sẽ hiển thị chữ cái đầu của tên người dùng
2. **Responsive**: Tự động điều chỉnh kích thước theo thiết bị
3. **Hover effects**: Hiệu ứng hover đẹp mắt
4. **Consistent styling**: Đồng nhất về mặt thiết kế
5. **Accessibility**: Hỗ trợ alt text và semantic HTML

## Helper Function

Component sử dụng helper function `getUserAvatar()` để xử lý đường dẫn avatar:

```php
function getUserAvatar($avatar = null, $defaultPath = 'images/avatar.png')
{
    if ($avatar && !empty($avatar)) {
        return asset('storage/' . $avatar);
    }
    return asset($defaultPath);
}
```

## CSS Classes

Component sử dụng các CSS classes sau:
- `.user-avatar`: Style cho avatar
- `.avatar-container`: Container cho avatar
- `.avatar-placeholder`: Placeholder khi không có avatar 
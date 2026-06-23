<!doctype html>
<html lang="en">

<head>

      @include("back-end.layout.head")
</head>

<body data-sidebar="dark">

      <!-- Script Zone -->
      @include("back-end.layout.script")
      <script>
            const url = '{{@$url}}';
            const parentCredentials = @json($parent_credentials ?? null);

            $(function() {
                  const options = {
                        title: "สำเร็จ",
                        text: "ระบบได้ทำการบันทึกข้อมูลเรียบร้อย",
                        icon: "success",
                        allowOutsideClick: false,
                  };

                  if (parentCredentials) {
                        options.text = undefined;
                        options.html = `
                              <div class="text-start">
                                    <p>ระบบสร้างบัญชีผู้ปกครองแล้ว กรุณาบันทึกข้อมูลนี้ทันที</p>
                                    <div><strong>Username:</strong> <code>${parentCredentials.username}</code></div>
                                    <div><strong>Email:</strong> <code>${parentCredentials.email}</code></div>
                                    <div><strong>Password:</strong> <code>${parentCredentials.password}</code></div>
                                    <small class="text-muted">รหัสผ่านจะแสดงเฉพาะครั้งนี้เท่านั้น</small>
                              </div>
                        `;
                  }

                  Swal.fire(options).then((result) => {
                        if (url == '') {
                              window.location = window.location.href;
                        } else {
                              window.location = url
                        }
                  });
            })
      </script>

</body>

</html>

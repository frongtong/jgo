<!-- Modal Zone -->
<div class="modal fade" id="modal_01" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded">
        </div>
    </div>
</div>
<!-- Modal Zone -->
<div class="modal fade" id="showpagemodal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="showpagemodalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="show_modal"></div>
    </div>
</div>

<div class="modal fade" id="showpagemodal1" data-bs-backdrop="static" tabindex="-1" aria-labelledby="showpagemodal1Label"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="show_modal1"></div>
    </div>
</div>

<div class="modal fade" id="secondmodal" data-bs-backdrop="static" aria-hidden="true" aria-labelledby="..."
    tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="show_secondmodal"></div>
    </div>
</div>
<script>
    var hostUrl = "assets/";
</script>

<script src="{{ asset('backend/assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('backend/assets/js/scripts.bundle.js') }}"></script>
<script src="{{ asset('backend/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>

{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/dropzone.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/dropzone.min.js"></script> --}}
{{-- <script src="{{ asset('public/ckeditor/ckeditor5.js') }}"></script>  --}}
{{-- <script src="https://cdn.ckeditor.com/ckfinder/ckfinder.js"></script> --}}

<script src="https://cdn.ckeditor.com/4.20.0/full/ckeditor.js"></script>
{{-- <script src="{{ asset('backend/js/ckeditor.js') }}"></script> --}}
{{-- <script src="https://cdn.ckeditor.com/4.25.0/standard/ckeditor.js"></script> --}}

<script src="{{ asset('backend/js/sweetalert.min.js') }}"></script>
{{-- <script src="{{ asset('backend/cusmike/sweetalert.min.js') }}"></script> --}}
{{-- <script src="{{ asset('dist/js/slip.js') }}"></script> --}}

{{-- <script src="https://cdn.jsdelivr.net/npm/slipjs@latest/slip.min.js"></script> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script> --}}
<script src="{{ asset('backend/js/slip.min.js') }}"></script>
<script src="{{ asset('backend/js/sortable.min.js') }}"></script>
{{-- <script src="{{ asset('backend/js/cloudsortable.min.js') }}"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.js"></script>
<script>
    $(function(){
        jQuery('img.svg').each(function(){
            var $img = jQuery(this);
            var imgID = $img.attr('id');
            var imgClass = $img.attr('class');
            var imgURL = $img.attr('src');

            jQuery.get(imgURL, function(data) {
                // Get the SVG tag, ignore the rest
                var $svg = jQuery(data).find('svg');

                // Add replaced image's ID to the new SVG
                if(typeof imgID !== 'undefined') {
                    $svg = $svg.attr('id', imgID);
                }
                // Add replaced image's classes to the new SVG
                if(typeof imgClass !== 'undefined') {
                    $svg = $svg.attr('class', imgClass+' replaced-svg');
                }

                // Remove any invalid XML tags as per http://validator.w3.org
                $svg = $svg.removeAttr('xmlns:a');

                // Check if the viewport is set, else we gonna set it if we can.
                if(!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
                    $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
                }

                // Replace image with new SVG
                $img.replaceWith($svg);

            }, 'xml');

        });
    });

    $(document).on("change", "input[type='file']", function () {
        let file = this.files[0];

        if (file) {
            let fileExtension = file.name.split(".").pop().toLowerCase();
            let fileSizeMB = parseFloat((file.size / (1024 * 1024)).toFixed(2));

            let allowedExtensions, maxSize;

            if (window.location.pathname.includes("home")) {
                allowedExtensions = ["jpg", "jpeg", "png", "webp", "mp4", "mov", "avi", "mkv"];
                maxSize = 100;
            } else if (window.location.pathname.includes("category2")) {
                allowedExtensions = ["svg"];
                maxSize = 10;
           } else if (window.location.pathname.includes("news_new")) {
                allowedExtensions = ["jpg", "jpeg", "png", "webp", "mp4", "mov", "avi", "mkv", "xlsx"];
                maxSize = 100;
            }  else {
                allowedExtensions = ["jpg", "jpeg", "png", "webp","svg"];
                maxSize = 10;
            }

            if (!allowedExtensions.includes(fileExtension)) {
                alert("อนุญาตให้อัปโหลดเฉพาะไฟล์: " + allowedExtensions.join(", "));
                $(this).val("");
                return;
            }

            if (fileSizeMB > maxSize) {
                alert("ขนาดไฟล์ต้องไม่เกิน " + maxSize + "MB (ไฟล์ของคุณ " + fileSizeMB + "MB)");
                $(this).val("");
                return;
            }
        }
    });

</script>
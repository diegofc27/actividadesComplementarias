<footer class="footer app-footer" id='footer' style="position: relative;">

        <span><a href="http://www.ith.mx/">Sistemas</a> &copy; 2019</span>
        <span class="ml-auto">Desarrollado por <a href="http://www.ith.mx/">Sistemas</a></span>
</footer>

<!-- GenesisUI main scripts -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="js/app.js"></script>
<script src="js/layout.js"></script>

@if (session('success'))
    <script>
        toastr.success('{{session('success')}}', 'Éxito!', {timeOut: 5000});
    </script>
@endif
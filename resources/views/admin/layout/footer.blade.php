  </main>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{url('website/js/data.js')}}"></script>
<script src="{{url('website/js/storage.js')}}"></script>
<script src="{{url('website/js/ui.js')}}"></script>
@yield('scripts')
@include('sweetalert::alert')
</body>
</html>

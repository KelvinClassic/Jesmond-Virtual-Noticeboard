<br />
<footer style="margin-top: 3rem;" class="container-fluid p-5" id="footer">
  <div class="row">
    <div class="col-sm-4 col-md-3">
      <div>
        <h6 class="fs-4 mb-3">Address</h6>
        <address class="text-light">
          St. George's Terrace,<br />
          Newcastle upon Tyne NE2<br />
          2DL
        </address>
      </div>
      <div>
        <h6 class="fs-4 mb-3">Opening Hours</h6>
        <div class="text-light">
          Tuesdays: 9:30 - 16:30<br />
          Thursdays: 9:30 - 16:30<br />
          Saturdays: 9:30 - 16:30
        </div>
      </div>
    </div>

    <div class="col-sm-4 col-md-3">
      <h6 class="fs-4 mb-3">Information</h6>
      <ul class="list-unstyled">
        <li class="mb-2">
          <a class="text-light link_special" href="{{ route('pages.index') }}">Jesmond Library</a>
        </li>
        <li class="mb-2"><a class="text-light link_special" href="{{ route('pages.events') }}">What's On</a></li>
        <li class="mb-2"><a class="text-light link_special" href="http://jesmondlibrary.org/blog/" target="_blank">Blog</a></li>
        <li class="mb-2"><a class="text-light link_special" href="http://jesmondlibrary.org/about-us/" target="_blank">About Us</a></li>
        <li class="mb-2"><a class="text-light link_special" href="http://jesmondlibrary.org/volunteer/" target="_blank">Volunteer</a></li>
        <li class="mb-2"><a class="text-light link_special" href="http://jesmondlibrary.org/room-hire/" target="_blank">Room Hire</a></li>
        <li class="mb-2"><a class="text-light link_special" href="{{ route('login') }}">Login</a></li>
        <li class="mb-2"><a class="text-light link_special" href="http://jesmondlibrary.org/downloads/" target="_blank">Downloads</a></li>
        <li class="mb-2"><a class="text-light link_special" href="http://jesmondlibrary.org/contact-us/" target="_blank">Contact Us</a></li>
      </ul>
    </div>

    <div class="col-sm-4 col-md-3">
      <h6 class="fs-4 mb-3">Upcoming Events</h6>
      <div><a href="{{ route('pages.index') }}#upcoming_events" class="text-light link_special">Click for more details</a></div>
    </div>

    <div class="col-sm-4 col-md-3">
      <h6 class="fs-4 mb-3">Follow Us</h6>
      <ul class="list-unstyled">
        <li class="mb-3">
          <a href="https://twitter.com/JesmondLibrary" target="_blank" class="text-decoration-none text-light d-flex align-items-center">
            <i style="font-size: 1.5rem;" class="fa-brands fa-twitter"></i>
            <span class="ms-2">Twitter</span>
          </a>
        </li>
        <li class="mb-3">
          <a href="https://www.facebook.com/JesmondLibrary" target="_blank" class="text-decoration-none text-light d-flex align-items-center">
            <i style="font-size: 1.5rem;" class="fa-brands fa-facebook"></i>
            <span class="ms-2">Facebook</span>
          </a>
        </li>
        <li class="mb-3">
          <a href="http://jesmondlibrary.us7.list-manage.com/subscribe?u=71ad5e5868710749a4d0f2c43&id=a818fdeedc" target="_blank" class="text-decoration-none text-light d-flex align-items-center">
            <i style="font-size: 1.5rem;" class="fa-solid fa-envelope"></i>
            <span class="ms-2">Mail</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</footer>
<div class="text-center p-3">
  2023 - Northumbria University Students.
</div>
</div>
{{-- close page width --}}

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

{{-- bootstrap js --}}
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ asset('js/showPassword.js') }}"></script>


{{-- jquery --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js" integrity="sha512-KBeR1NhClUySj9xBB0+KRqYLPkM6VvXiiWaSz/8LCQNdRpUm38SWUrj0ccNDNSkwCD9qPA4KobLliG26yPppJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@yield('js')

</body>

</html>
      <!-- Footer -->
      <footer class="main-footer">
        <div class="footer-left">
          Copyright &copy; 2024 <div class="bullet"></div> SiPagu - Sistem Pengelolaan Keuangan
        </div>
        <div class="footer-right">
          Version 1.0.0
        </div>
      </footer>
    </div>
  </div>

  <!-- General JS Scripts -->
  <script src="../assets/modules/jquery.min.js"></script>
  <script src="../assets/modules/popper.js"></script>
  <script src="../assets/modules/tooltip.js"></script>
  <script src="../assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="../assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="../assets/modules/moment.min.js"></script>
  <script src="../assets/js/stisla.js"></script>
  
  <!-- JS Libraies -->
  <script src="../assets/modules/jquery.sparkline.min.js"></script>
  <script src="../assets/modules/chart.min.js"></script>
  <script src="../assets/modules/owlcarousel2/dist/owl.carousel.min.js"></script>
  <script src="../assets/modules/summernote/summernote-bs4.js"></script>
  <script src="../assets/modules/chocolat/dist/js/jquery.chocolat.min.js"></script>

  <!-- Template JS File -->
  <script src="../assets/js/scripts.js"></script>
  <script src="../assets/js/custom.js"></script>
  
  <!-- Page Specific JS File -->
  <?php
  // Include page-specific JS based on current page
  $current_page = basename($_SERVER['PHP_SELF']);
  $js_file = str_replace('.php', '.js', $current_page);
  $js_path = "../assets/js/page/" . $js_file;
  
  if (file_exists($js_path)) {
    echo '<script src="' . $js_path . '"></script>';
  }
  ?>
</body>
</html>
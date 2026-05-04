{{-- resources/views/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EQUApp | Dashboard</title>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      background: #fff;
      color: #222;
      overflow-x: hidden;
    }

    .layout {
      display: flex;
      min-height: 100vh;
    }

    /* SIDEBAR */
    .sidebar {
      width: 260px;
      height: 100vh;
      position: fixed;
      left: 0;
      top: 0;
      background: linear-gradient(90deg, #6ee89a, #95d6f4);
      padding: 40px 35px;
      color: white;
      z-index: 10;
    }

    .logo {
      width: 130px;
      margin-bottom: 130px;
    }

    .sidebar ul {
      list-style: none;
    }

    .sidebar li {
      margin-bottom: 16px;
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      font-size: 28px;
      font-weight: 700;
      transition: .2s;
    }

    .sidebar .sidebar-link {
      color: white;
      text-decoration: none;
      font-size: 28px;
      font-weight: 700;
      transition: .2s;
    }

    .sidebar .sidebar-link:hover {
      color: #72ee9c;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      color: #72ee9c;
    }

    .profile-icon {
      position: absolute;
      bottom: 45px;
      left: 45px;
      width: 45px;
      height: 45px;
      border-radius: 50%;
      background: white;
    }

    /* CONTENT */
    .content {
      margin-left: 260px;
      width: calc(100% - 260px);
    }

    section {
      min-height: 100vh;
      padding: 0 40px;
      display: none;
      animation: fade .4s ease;
    }

    section.active {
      display: block;
    }

    @keyframes fade {
      from { opacity: 0; transform: translateY(15px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .section-bar {
      height: 35px;
      line-height: 35px;
      color: white;
      font-weight: bold;
      letter-spacing: 2px;
      padding-left: 35px;
      margin-bottom: 50px;
    }

    .blue { background: linear-gradient(90deg, #6ee89a, #95d6f4); }
    .green { background: linear-gradient(90deg, #95d6f4, #6ee89a); }
    .orange { background: linear-gradient(90deg, #6ee89a, #ffc176); }

    /* SDGS */
    .sdgs-wrap {
      display: flex;
      align-items: center;
      gap: 70px;
      background: #9bd8f2;
      padding: 90px 70px;
      min-height: 85vh;
    }

    .sdgs-wrap img {
      width: 420px;
    }

    .sdgs-text h1 {
      font-size: 58px;
      color: white;
    }

    .sdgs-text h3 {
      font-size: 28px;
      color: #3e5870;
      margin-bottom: 30px;
    }

    .sdgs-text p {
      font-size: 26px;
      line-height: 1.45;
      color: white;
      max-width: 850px;
    }

    /* EQUITY */
    .hero {
      height: 380px;
      background: url("{{ asset('images/polusi.jpg') }}") center/cover no-repeat;
      display: flex;
      align-items: center;
      justify-content: flex-end;
      padding: 60px;
      color: white;
      text-align: right;
      font-size: 28px;
      font-weight: bold;
    }

    .equity-info {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 80px 30px;
    }

    .equity-info p {
      color: #008dcc;
      font-size: 28px;
      font-weight: bold;
      max-width: 820px;
      line-height: 1.35;
    }

    .equity-info .icon {
      font-size: 110px;
      color: #008dcc;
    }

    .indicator-section {
      background: #ffffff;
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
      margin-bottom: 40px;
    }

    .indicator-section h2 {
      font-size: 28px;
      margin-bottom: 12px;
      color: #0f172a;
    }

    .indicator-section p {
      color: #475569;
      line-height: 1.75;
      margin-bottom: 30px;
      max-width: 760px;
    }

    .indicator-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 15px;
      color: #0f172a;
    }

    .indicator-table th,
    .indicator-table td {
      padding: 16px 18px;
      border-bottom: 1px solid #e2e8f0;
      text-align: left;
      vertical-align: top;
    }

    .indicator-table th {
      background: #f8fafc;
      color: #334155;
      font-weight: 700;
    }

    .indicator-table tr:hover {
      background: #f8fafc;
    }

    .indicator-category {
      background: #eff6ff;
      color: #1d4ed8;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.08em;
    }

    .status-chip {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 14px;
      border-radius: 999px;
      font-size: 13px;
      font-weight: 700;
    }

    .status-chip.green {
      background: #dcfce7;
      color: #166534;
    }

    .status-chip.yellow {
      background: #fef9c3;
      color: #92400e;
    }

    .status-chip.red {
      background: #fee2e2;
      color: #b91c1c;
    }

    /* ABOUT */
    .mentor {
      display: flex;
      align-items: center;
      gap: 150px;
      padding: 40px 120px;
    }

    .mentor-card {
      position: relative;
    }

    .mentor-card img {
      width: 290px;
      height: 360px;
      object-fit: cover;
      border-radius: 0 0 15px 15px;
    }

    .mentor-card span {
      position: absolute;
      bottom: 20px;
      left: 20px;
      color: white;
      font-weight: bold;
      font-size: 18px;
    }

    .mentor h1 {
      font-size: 34px;
    }

    .mentor h1 span {
      color: #00aeea;
    }

    .team-area {
      background: #d6dee8;
      padding: 60px;
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 35px;
    }

    .team-card {
      background: rgba(255,255,255,.25);
      padding: 28px;
      text-align: center;
      color: white;
      border-radius: 5px;
      box-shadow: 0 15px 30px rgba(0,0,0,.08);
    }

    .team-card h4 {
      margin-bottom: 18px;
    }

    /* FAQ */
    .faq-wrap {
      display: grid;
      grid-template-columns: 320px 1fr;
      gap: 60px;
      padding: 80px 0;
    }

    .faq-left {
      border-right: 1px solid #ddd;
      padding-right: 40px;
    }

    .faq-left h3 {
      margin-bottom: 25px;
      color: #42526b;
    }

    .faq-left p {
      color: #75859a;
      line-height: 1.6;
    }

    .faq-item {
      border-bottom: 1px solid #eee;
      padding: 25px 0;
      cursor: pointer;
    }

    .faq-question {
      display: flex;
      justify-content: space-between;
      font-size: 19px;
    }

    .faq-answer {
      display: none;
      color: #666;
      margin-top: 15px;
      line-height: 1.6;
    }

    .faq-item.open .faq-answer {
      display: block;
    }

    /* CONTACT */
    .contact-desc {
      color: #526174;
      font-weight: bold;
      margin: 45px 0;
    }

    .contact-wrap {
      display: grid;
      grid-template-columns: 260px 1fr;
      gap: 60px;
      align-items: start;
    }

    .contact-title {
      font-size: 30px;
      color: #5d6f87;
      font-weight: bold;
    }

    form {
      border-left: 2px solid #ffd5a5;
      padding-left: 55px;
      position: relative;
    }

    label {
      display: block;
      margin-bottom: 8px;
      color: #8da0b8;
      font-weight: bold;
    }

    input, textarea {
      width: 100%;
      padding: 13px;
      margin-bottom: 25px;
      border: 1px solid #d5dce5;
      border-radius: 5px;
      background: #f3f6fa;
      font-size: 16px;
    }

    textarea {
      height: 80px;
      resize: vertical;
    }

    button {
      background: #ffb174;
      color: white;
      border: none;
      padding: 13px 28px;
      border-radius: 4px;
      font-weight: bold;
      cursor: pointer;
    }

    @media(max-width: 900px) {
      .sidebar {
        width: 210px;
      }

      .content {
        margin-left: 210px;
        width: calc(100% - 210px);
      }

      .sdgs-wrap,
      .mentor,
      .equity-info {
        flex-direction: column;
        text-align: center;
      }

      .team-area {
        grid-template-columns: 1fr;
      }

      .faq-wrap,
      .contact-wrap {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>

<div class="layout">

  <aside class="sidebar">
    <img src="{{ asset('images/dikris-logo.png') }}" class="logo" alt="Logo">

    <ul>
      <li><a href="{{ route('lokasi') }}" class="sidebar-link">Lokasi</a></li>
      <li><a href="{{ route('perangkat') }}" class="sidebar-link">Perangkat</a></li>
      @foreach ($sections as $id => $name)
        <li>
          <a href="#{{ $id }}" class="nav-link" data-target="{{ $id }}">
            {{ $name }}
          </a>
        </li>
      @endforeach
    </ul>

    <div class="profile-icon"></div>
  </aside>

  <main class="content">

    <section id="feature" class="active">
      <div class="section-bar blue">0 FEATURE</div>
      <div class="sdgs-wrap">
        <div class="sdgs-text">
          <h1>EQUApp</h1>
          <h3>Environmental Quality Application</h3>
          <p>
            A web-based IoT monitoring platform for real-time environmental
            quality tracking, especially water and air quality.
          </p>
        </div>
      </div>
    </section>

    <section id="sdgs">
      <div class="section-bar blue">1 SDGs</div>
      <div class="sdgs-wrap">
        <img src="{{ asset('images/sdgs.png') }}" alt="SDGs">
        <div class="sdgs-text">
          <h1>SDGs</h1>
          <h3>Sustainable Development Goals</h3>
          <p>
            This Equity project leverages Internet of Things to provide real-time
            environmental monitoring and support data-driven decisions. It contributes
            to SDG 6 Clean Water and Sanitation and SDG 3 Good Health and Well-being.
          </p>
        </div>
      </div>
    </section>

    <section id="equityproject">
      <div class="section-bar blue">2 EQUITY Project</div>
      <div class="hero">
        Environmental monitoring still relies on manual and periodic methods,
        making the process inefficient and unable to provide real-time insights.
      </div>

      <div class="equity-info">
        <p>
          Traditional monitoring methods often fail to capture critical changes
          in air and water quality, leading to environmental risks and potential
          health issues for communities.
        </p>
        <div class="icon">☢</div>
      </div>

      <div class="indicator-section">
        <h2>Daftar Indikator Sensor</h2>
        <p>Semua indikator ditulis dengan satuan yang umum digunakan dan penjelasan sederhana agar mudah dimengerti oleh pengguna dashboard.</p>

        <table class="indicator-table">
          <thead>
            <tr>
              <th>Indikator</th>
              <th>Satuan</th>
              <th>Keterangan</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr class="indicator-category">
              <td colspan="4">AQUA VISKA – Sensor Kualitas Air</td>
            </tr>
            <tr>
              <td>Suhu Air</td>
              <td>°C</td>
              <td>Temperatur air permukaan yang diukur di lokasi.</td>
              <td><span class="status-chip green">Normal</span></td>
            </tr>
            <tr>
              <td>pH</td>
              <td>skala 0–14</td>
              <td>Tingkat keasaman atau kebasaan air tanpa satuan.</td>
              <td><span class="status-chip green">Normal</span></td>
            </tr>
            <tr>
              <td>Kekeruhan (Turbidity)</td>
              <td>NTU</td>
              <td>Seberapa keruh air; nilai lebih tinggi berarti air lebih keruh.</td>
              <td><span class="status-chip yellow">Waspada</span></td>
            </tr>
            <tr>
              <td>Dissolved Oxygen (DO)</td>
              <td>mg/L</td>
              <td>Jumlah oksigen terlarut yang tersedia di dalam air.</td>
              <td><span class="status-chip green">Normal</span></td>
            </tr>
            <tr>
              <td>Total Dissolved Solids (TDS)</td>
              <td>ppm</td>
              <td>Kadar mineral dan zat terlarut dalam air.</td>
              <td><span class="status-chip yellow">Waspada</span></td>
            </tr>

            <tr class="indicator-category">
              <td colspan="4">IOT CLIMATE – Sensor Kualitas Udara & Iklim</td>
            </tr>
            <tr>
              <td>Suhu Udara</td>
              <td>°C</td>
              <td>Temperatur udara di sekitar lokasi sensor.</td>
              <td><span class="status-chip green">Normal</span></td>
            </tr>
            <tr>
              <td>Kelembapan</td>
              <td>% RH</td>
              <td>Persentase uap air di udara.</td>
              <td><span class="status-chip green">Normal</span></td>
            </tr>
            <tr>
              <td>TVOC</td>
              <td>mg/m³</td>
              <td>Kadar senyawa organik volatil di udara.</td>
              <td><span class="status-chip yellow">Waspada</span></td>
            </tr>
            <tr>
              <td>CO₂</td>
              <td>ppm</td>
              <td>Kadar karbon dioksida di udara.</td>
              <td><span class="status-chip yellow">Waspada</span></td>
            </tr>
            <tr>
              <td>UV Index</td>
              <td>skala</td>
              <td>Intensitas sinar ultraviolet yang mencapai permukaan.</td>
              <td><span class="status-chip red">Tinggi</span></td>
            </tr>
            <tr>
              <td>Kecepatan Angin</td>
              <td>m/s</td>
              <td>Kecepatan angin di sekitar area sensor.</td>
              <td><span class="status-chip green">Normal</span></td>
            </tr>
            <tr>
              <td>Curah Hujan</td>
              <td>mm</td>
              <td>Jumlah hujan yang tercatat dalam periode tertentu.</td>
              <td><span class="status-chip green">Normal</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <section id="about-us">
      <div class="section-bar blue">3 About Us</div>

      <div class="mentor">
        <div class="mentor-card">
          <img src="{{ asset('images/mentor.png') }}" alt="Mentor">
          <span>Our Mentor</span>
        </div>

        <div>
          <h1>Mr. <span>Cucuk Wawan Budiyanto</span> ST., PH.D.</h1>
          <p>Lecturer in the informatics and computer engineering education study program</p>
        </div>
      </div>

      <div class="team-area">
        @foreach ($teamMembers as $member)
          <div class="team-card">
            <h4>{{ $member['role'] }}</h4>
            <p>{{ $member['name'] }}</p>
          </div>
        @endforeach
      </div>
    </section>

    <section id="faq">
      <div class="section-bar green">4 FAQ</div>

      <div class="faq-wrap">
        <div class="faq-left">
          <h3>Frequently Asked Questions</h3>
          <p>Here are some of our FAQs. If you have any other questions, please feel free to contact us.</p>
        </div>

        <div>
          @foreach ($faqs as $question => $answer)
            <div class="faq-item">
              <div class="faq-question">
                <span>{{ $question }}</span>
                <strong>+</strong>
              </div>
              <div class="faq-answer">{{ $answer }}</div>
            </div>
          @endforeach
        </div>
      </div>
    </section>

    <section id="contact">
      <div class="section-bar orange">5 Contact Us</div>

      <p class="contact-desc">
        If you have any questions or would like to get in touch with us, please fill out the form below and we will get back to you as soon as possible.
      </p>

      <div class="contact-wrap">
        <div class="contact-title">Get in Touch</div>

        <form method="post">
          @csrf
          <label>Name</label>
          <input type="text" name="name">

          <label>Email</label>
          <input type="email" name="email">

          <label>Message</label>
          <textarea name="message"></textarea>

          <button type="submit" name="send">Send Message</button>
        </form>
      </div>
    </section>

  </main>
</div>

<script>
  const links = document.querySelectorAll(".nav-link");
  const sections = document.querySelectorAll("section");

  function showSection(id) {
    sections.forEach(section => {
      section.classList.remove("active");
    });

    links.forEach(link => {
      link.classList.remove("active");
    });

    const target = document.getElementById(id);
    const activeLink = document.querySelector(`[data-target="${id}"]`);

    if (target) target.classList.add("active");
    if (activeLink) activeLink.classList.add("active");
  }

  links.forEach(link => {
    link.addEventListener("click", function(e) {
      e.preventDefault();

      const target = this.dataset.target;
      history.pushState(null, "", "#" + target);
      showSection(target);
    });
  });

  document.querySelectorAll(".faq-item").forEach(item => {
    item.addEventListener("click", function() {
      this.classList.toggle("open");
    });
  });

  window.addEventListener("load", () => {
    const hash = location.hash.replace("#", "");
    showSection(hash || "feature");
  });
</script>

</body>
</html>
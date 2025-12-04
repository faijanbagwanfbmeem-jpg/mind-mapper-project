<?php
// If you prefer the header/footer from your existing project remove this file's header/footer
// and include your existing inc/header.php & inc/footer.php
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>MindMapper Career Counseling — IQ & EQ Assessment</title>

<!-- Google font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

<style>
:root{
  --blue-600:#0b66ff;
  --blue-500:#1e90ff;
  --navy:#0a2342;
  --muted:#677085;
  --card-bg:#ffffff;
  --accent:#00bcd4;
  --radius:12px;
  --maxw:1200px;
}
*{box-sizing:border-box}
html,body{height:100%}
body{
  margin:0;
  font-family:Inter,system-ui,Arial,Helvetica,sans-serif;
  background: linear-gradient(180deg,#f5f8ff 0%, #f3f6fb 100%);
  color:#0b2035;
  -webkit-font-smoothing:antialiased;
  -moz-osx-font-smoothing:grayscale;
  line-height:1.5;
}

/* layout */
.container{width:92%;max-width:var(--maxw);margin:0 auto}

/* header */
.site-header{
  background: linear-gradient(90deg,var(--blue-600), #2a8cff);
  padding:18px 0;
  color:#fff;
  position:sticky;
  top:0;
  z-index:40;
  box-shadow:0 6px 20px rgba(12,44,102,0.12);
}
.header-inner{display:flex;align-items:center;justify-content:space-between;gap:16px}
.brand{display:flex;align-items:center;gap:14px}
.brand img{height:44px;width:44px;object-fit:contain;border-radius:8px;background:white;padding:6px}
.brand h1{font-size:18px;margin:0;font-weight:700;color:#fff}
.nav{display:flex;gap:14px;align-items:center}
.nav a{color:rgba(255,255,255,0.95);text-decoration:none;font-weight:600}
.nav .cta{background:rgba(255,255,255,0.12);padding:8px 12px;border-radius:8px}

@media(max-width:880px){
  .nav{display:none}
}

/* hero */
.hero{
  display:grid;
  grid-template-columns: 1fr 480px;
  gap:28px;
  align-items:center;
  padding:64px 0;
}
@media(max-width:1000px){
  .hero{grid-template-columns:1fr; text-align:center}
}
.hero-left{padding-top:12px}
.kicker{
  display:inline-block;
  background:rgba(255,255,255,0.12);
  color:#eaf4ff;
  padding:8px 12px;border-radius:999px;font-weight:700;margin-bottom:18px;
  box-shadow:0 6px 18px rgba(20,60,120,0.08);
}
h2.hero-title{font-size:44px;margin:0 0 14px;line-height:1.05;color:#041a33}
p.lead{margin:0 0 20px;color:var(--muted);font-size:18px}
.btn-row{display:flex;gap:12px;flex-wrap:wrap;justify-content:flex-start}
@media(max-width:1000px){ .btn-row{justify-content:center} }

.btn-primary{
  background:linear-gradient(90deg,var(--blue-600),var(--blue-500));
  color:#fff;padding:12px 20px;border-radius:10px;border:none;font-weight:700;cursor:pointer;box-shadow:0 8px 18px rgba(11,102,255,0.18);
}
.btn-ghost{background:transparent;color:#fff;padding:12px 18px;border-radius:10px;border:1px solid rgba(255,255,255,0.16);cursor:pointer}

/* hero image box */
.hero-right{
  display:flex;align-items:center;justify-content:center;
}
.card-visual{
  width:420px;height:320px;border-radius:18px;overflow:hidden;
  background:linear-gradient(135deg,#ffffff,#f0f8ff);
  box-shadow:0 18px 40px rgba(12,44,102,0.12);
  position:relative;padding:18px;display:flex;flex-direction:column;gap:10px;
}

/* decorative brain image */
.visual-top{display:flex;gap:12px;align-items:center}
.visual-top img.left{width:120px;height:120px;object-fit:cover;border-radius:10px}
.visual-top .meta{flex:1}
.meta h4{margin:0;font-size:16px;color:#08304d}
.meta p{margin:6px 0 0;color:var(--muted);font-size:13px}

/* stylized graph */
.graph{
  flex:1;display:flex;align-items:center;justify-content:center;
}
.graph img{max-width:100%;height:auto;display:block}

/* features */
.section{margin:34px 0;padding:28px;border-radius:14px;background:linear-gradient(180deg,#fff,#fbfdff);box-shadow:0 8px 30px rgba(12,44,102,0.04)}
.features-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
  gap:18px;
}
.feature{
  padding:18px;border-radius:12px;background:linear-gradient(180deg,#fff,#f6fbff);
  box-shadow:0 8px 18px rgba(6,50,120,0.04);
}
.feature h3{margin:0 0 8px;font-size:18px}
.feature p{margin:0;color:var(--muted);font-size:14px}

/* how it works */
.how{
  display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-top:16px
}
.step{background:#fff;padding:18px;border-radius:12px;box-shadow:0 6px 18px rgba(10,30,80,0.04);text-align:center}
.step h4{margin:8px 0;color:#07304a}
.step p{margin:0;color:var(--muted)}

/* testimonials */
.testimonials{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:18px}
.testimonial{background:linear-gradient(180deg,#fff,#fbfdff);padding:18px;border-radius:12px;box-shadow:0 8px 18px rgba(6,50,120,0.04)}
.testimonial p{margin:0;color:var(--muted)}

/* footer */
.site-footer{padding:28px 0;margin-top:36px;color:#33475b}
.footer-inner{display:flex;justify-content:space-between;align-items:center;gap:8px}
.socials a{margin-left:10px;text-decoration:none;color:var(--navy);font-weight:600}

/* small screens */
@media(max-width:720px){
  .how{grid-template-columns:1fr}
  .testimonials{grid-template-columns:1fr}
  .footer-inner{flex-direction:column;align-items:flex-start}
}
</style>
</head>

<body>
<header class="site-header">
  <div class="container header-inner">
    <div class="brand">
      <img src="https://i.ibb.co/TM2FjWf/mindmap-logo.png" alt="MindMapper">
      <div>
        <h1>MindMapper</h1>
        <div style="font-size:12px;color:rgba(255,255,255,0.9);margin-top:4px">IQ &amp; EQ Career Assessment</div>
      </div>
    </div>

    <nav class="nav">
      <a href="index.php">Home</a>
      <a href="about.php">About</a>
      <a href="services.php">Services</a>
      <a href="student/register.php">Register</a>
      <a href="student/login.php">Login</a>
      <a href="admin/login.php" class="cta">Admin</a>
    </nav>
  </div>
</header>

<main class="container">

  <!-- HERO -->
  <section class="hero">
    <div class="hero-left">
      <span class="kicker">Trusted by schools & counselors</span>
      <h2 class="hero-title">Discover your strengths — the right career starts with understanding your mind</h2>
      <p class="lead">Take our scientifically designed IQ and EQ assessments, get a personalized scorecard and receive professional counselling to guide your academic and career choices.</p>

      <div class="btn-row" role="group" aria-label="Hero actions">
        <a href="student/register.php"><button class="btn-primary">Get Start — Register</button></a>
        <a href="student/login.php"><button class="btn-ghost">Student Login</button></a>
      </div>

      <div style="margin-top:20px;color:var(--muted);font-size:14px">
        <strong>Free demo:</strong> 10 IQ &amp; 10 EQ sample questions to try before the full assessment.
      </div>
    </div>

    <div class="hero-right">
      <div class="card-visual" role="img" aria-label="Mind mapping visual">
        <div class="visual-top">
          <img class="left" src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?crop=entropy&cs=tinysrgb&fit=crop&fm=jpg&h=480&q=60&w=640" alt="students working">
          <div class="meta">
            <h4>MindMapper Diagnostic</h4>
            <p>IQ + EQ combined analysis with brain dominance mapping for personalized recommendations.</p>
          </div>
        </div>

        <div class="graph" style="margin-top:6px">
          <img src="https://i.ibb.co/ftcS0RN/brain-graphic.png" alt="brain diagram" style="max-width:280px">
        </div>
      </div>
    </div>
  </section>

  <!-- Features -->
  <section class="section">
    <div style="display:flex;justify-content:space-between;align-items:center">
      <h2 style="margin:0">What we offer</h2>
      <div style="color:var(--muted)">Accurate • Fast • Actionable</div>
    </div>

    <div class="features-grid" style="margin-top:18px">
      <div class="feature">
        <h3>IQ Assessment</h3>
        <p>Test logic, pattern solving and numerical reasoning. Scientifically scored.</p>
      </div>

      <div class="feature">
        <h3>EQ Assessment</h3>
        <p>Measure emotional awareness, empathy and problem handling under stress.</p>
      </div>

      <div class="feature">
        <h3>Brain Dominance</h3>
        <p>Left/right brain tendencies that explain creative or analytical strengths.</p>
      </div>

      <div class="feature">
        <h3>Personalized Report</h3>
        <p>Printable A4 scorecard with interpretation and career suggestions.</p>
      </div>
    </div>
  </section>

  <!-- How it works -->
  <section class="section" aria-labelledby="how-title">
    <h2 id="how-title">How it works</h2>

    <div class="how">
      <div class="step">
        <div style="font-size:28px">1</div>
        <h4>Register</h4>
        <p>Create your student profile in under a minute.</p>
      </div>

      <div class="step">
        <div style="font-size:28px">2</div>
        <h4>Take Tests</h4>
        <p>Complete 10 IQ and 10 EQ questions with an easy timer and progress bar.</p>
      </div>

      <div class="step">
        <div style="font-size:28px">3</div>
        <h4>Get Report</h4>
        <p>Receive an A4 printable scorecard and counseling recommendations.</p>
      </div>
    </div>
  </section>

  <!-- Testimonials -->
  <section class="section">
    <h2>Testimonials</h2>
    <div class="testimonials">
      <div class="testimonial">
        <strong>Ritu Sharma — Student</strong>
        <p>“The MindMapper report helped me pick the right stream. The counselor was insightful and the process was quick.”</p>
      </div>

      <div class="testimonial">
        <strong>Akash Gupta — Parent</strong>
        <p>“Clear scorecard and practical suggestions. The counselors helped build my son's confidence.”</p>
      </div>
    </div>
  </section>

</main>

<footer class="site-footer">
  <div class="container footer-inner">
    <div>
      <strong>MindMapper</strong><br>
      <small style="color:var(--muted)">IQ & EQ Career Assessment • © <?= date('Y') ?> MindMapper</small>
    </div>

    <div class="socials">
      <a href="#" aria-label="facebook">Facebook</a>
      <a href="#" aria-label="instagram">Instagram</a>
      <a href="#" aria-label="linkedin">LinkedIn</a>
    </div>
  </div>
</footer>

</body>
</html>

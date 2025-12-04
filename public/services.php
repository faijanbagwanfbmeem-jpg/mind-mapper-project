<?php include "inc/header.php"; ?>

<style>
.page-container{
    max-width: 1100px;
    margin: 60px auto;
    padding: 40px;
}

.section-title{
    text-align: center;
    font-size: 32px;
    margin-bottom: 25px;
    color:#0B132B;
}

.services-grid{
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 25px;
}

.card{
    background: white;
    padding: 25px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

.card h3{
    font-size: 22px;
    color:#0B132B;
    margin-bottom: 10px;
}

.card p{
    font-size: 16px;
    color:#444;
}
</style>

<div class="page-container">
    <h2 class="section-title">Our Services</h2>

    <div class="services-grid">

        <div class="card">
            <h3>IQ Assessment</h3>
            <p>Evaluate logical thinking, analytical power, and problem-solving ability.</p>
        </div>

        <div class="card">
            <h3>EQ Assessment</h3>
            <p>Measure emotional intelligence, communication, empathy, and self-awareness.</p>
        </div>

        <div class="card">
            <h3>Brain Dominance Test</h3>
            <p>Analyze left-brain/right-brain tendencies to understand creativity & logic strengths.</p>
        </div>

        <div class="card">
            <h3>Career Counseling</h3>
            <p>Expert counselor support to guide students towards the right academic & career path.</p>
        </div>

    </div>
</div>

<?php include "inc/footer.php"; ?>

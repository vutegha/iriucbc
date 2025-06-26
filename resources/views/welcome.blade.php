@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="bg-primary text-white py-5">
    <div class="container text-center">
        <h1 class="display-4 font-weight-bold">Institut de Recherche Intégrée (IRI-UCBC)</h1>
        <p class="lead mt-3">
            Integrated Research Institute at Université Chrétienne Bilingue du Congo (UCBC) dedicated to advancing research and innovation for sustainable development in the DRC.
        </p>
        <a href="#" class="btn btn-light btn-lg mt-4">Discover Our Mission</a>
    </div>
</div>

<!-- About Section -->
<div class="container my-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h2>About IRI-UCBC</h2>
            <p>
                The Institut de Recherche Intégrée (IRI-UCBC) is committed to promoting excellence in education, research, and technology for sustainable development in the Democratic Republic of Congo. As part of UCBC, IRI-UCBC fosters innovation, collaboration, and capacity building to address local and global challenges through impactful research and community engagement.
            </p>
            <a href="#" class="btn btn-primary mt-3">Learn More</a>
        </div>
        <div class="col-md-6">
            <img src="https://www.iriucbc.org/images/iriucbc-campus.jpg" alt="IRI-UCBC Campus" class="img-fluid rounded shadow">
        </div>
    </div>
</div>

<!-- Research & Initiatives Section -->
<div class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-4">Research & Initiatives</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="https://www.iriucbc.org/images/research.jpg" class="card-img-top" alt="Research">
                    <div class="card-body">
                        <h5 class="card-title">Applied Research</h5>
                        <p class="card-text">Conducting research in various fields to address real-world problems in the DRC.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">View Research</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="https://www.iriucbc.org/images/education.jpg" class="card-img-top" alt="Education">
                    <div class="card-body">
                        <h5 class="card-title">Education & Training</h5>
                        <p class="card-text">Empowering students and professionals through quality education, workshops, and skills development.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">Our Programs</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="https://www.iriucbc.org/images/community.jpg" class="card-img-top" alt="Community">
                    <div class="card-body">
                        <h5 class="card-title">Community Engagement</h5>
                        <p class="card-text">Collaborating with local communities and partners to promote technology for social good and sustainable development.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">Get Involved</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Newsletter Section -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h2>Stay Connected</h2>
            <p>Subscribe to receive updates on research, educational programs, and technology initiatives from IRI-UCBC.</p>
            <form class="form-inline justify-content-center">
                <input type="email" class="form-control mb-2 mr-sm-2" placeholder="Enter your email">
                <button type="submit" class="btn btn-primary mb-2">Subscribe</button>
            </form>
        </div>
    </div>
</div>
@endsection
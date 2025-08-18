/*
Copyright 2017 Ziadin Givan

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

   http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

https://github.com/givanz/Vvvebjs
*/

//Snippets from https://bootsnipp.com/license

Vvveb.BlocksGroup["Bootstrap"] = [
	"bootstrap4/product-card",
	"bootstrap4/login-form",
	"bootstrap4/about-team",
	"bootstrap4/pricing-1",
	"bootstrap4/loading-circle",
	"bootstrap4/block-quote",
	"bootstrap4/subscribe-newsletter",
];

Vvveb.Blocks.add("bootstrap4/product-card", {
	name: "Product Cards with Transition",
	image: "https://d2d3qesrx8xj6s.cloudfront.net/img/screenshots/0c3153bcb2ed97483a82b1f4ea966f8187379792.png",
	html: `
<div class="container">
	<div class="row ads">
    <!-- Category Card -->
    <div class="col-md-4">
        <div class="card rounded">
            <div class="card-image">
                <span class="card-notify-badge">Low KMS</span>
                <span class="card-notify-year">2018</span>
                <img class="img-fluid" src="https://imageonthefly.autodatadirect.com/images/?USER=eDealer&PW=edealer872&IMG=USC80HOC011A021001.jpg&width=440&height=262" alt="Alternate Text" />
            </div>
            <div class="card-image-overlay m-auto">
                <span class="card-detail-badge">Used</span>
                <span class="card-detail-badge">$28,000.00</span>
                <span class="card-detail-badge">13000 Kms</span>
            </div>
            <div class="card-body text-center">
                <div class="ad-title m-auto">
                    <h5>Honda Accord LX</h5>
                </div>
                <a class="ad-btn" href="#">View</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card rounded">
            <div class="card-image">
                <span class="card-notify-badge">Fully-Loaded</span>
                <span class="card-notify-year">2017</span>
                <img class="img-fluid" src="https://imageonthefly.autodatadirect.com/images/?USER=eDealer&PW=edealer872&IMG=CAC80HOC021B121001.jpg&width=440&height=262" alt="Alternate Text" />
            </div>
            <div class="card-image-overlay m-auto">
                <span class="card-detail-badge">Used</span>
                <span class="card-detail-badge">$28,000.00</span>
                <span class="card-detail-badge">13000 Kms</span>
            </div>
            <div class="card-body text-center">
                <div class="ad-title m-auto">
                    <h5>Honda CIVIC HATCHBACK LS</h5>
                </div>
                <a class="ad-btn" href="#">View</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card rounded">
            <div class="card-image">
                <span class="card-notify-badge">Price Reduced</span>
                <span class="card-notify-year">2018</span>
                <img class="img-fluid" src="https://imageonthefly.autodatadirect.com/images/?USER=eDealer&PW=edealer872&IMG=USC80HOC091A021001.jpg&width=440&height=262" alt="Alternate Text" />
            </div>
            <div class="card-image-overlay m-auto">
                <span class="card-detail-badge">Used</span>
                <span class="card-detail-badge">$22,000.00</span>
                <span class="card-detail-badge">8000 Kms</span>
            </div>
            <div class="card-body text-center">
                <div class="ad-title m-auto">
                    <h5>Honda Accord Hybrid LT</h5>
                </div>
                <a class="ad-btn" href="#">View</a>
            </div>
        </div>
    </div>

</div>
<style>
.ads {
    margin: 30px 0 30px 0;
   
}

.ads .card-notify-badge {
        position: absolute;
        left: -10px;
        top: -20px;
        background: #f2d900;
        text-align: center;
        border-radius: 30px 30px 30px 30px; 
        color: #000;
        padding: 5px 10px;
        font-size: 14px;

    }

.ads .card-notify-year {
        position: absolute;
        right: -10px;
        top: -20px;
        background: #ff4444;
        border-radius: 50%;
        text-align: center;
        color: #fff;      
        font-size: 14px;      
        width: 50px;
        height: 50px;    
        padding: 15px 0 0 0;
}


.ads .card-detail-badge {      
        background: #f2d900;
        text-align: center;
        border-radius: 30px 30px 30px 30px;
        color: #000;
        padding: 5px 10px;
        font-size: 14px;        
    }

   

.ads .card:hover {
            background: #fff;
            box-shadow: 12px 15px 20px 0px rgba(46,61,73,0.15);
            border-radius: 4px;
            transition: all 0.3s ease;
        }

.ads .card-image-overlay {
        font-size: 20px;
        
    }


.ads .card-image-overlay span {
            display: inline-block;              
        }


.ads .ad-btn {
        text-transform: uppercase;
        width: 150px;
        height: 40px;
        border-radius: 80px;
        font-size: 16px;
        line-height: 35px;
        text-align: center;
        border: 3px solid #e6de08;
        display: block;
        text-decoration: none;
        margin: 20px auto 1px auto;
        color: #000;
        overflow: hidden;        
        position: relative;
        background-color: #e6de08;
    }

.ads .ad-btn:hover {
            background-color: #e6de08;
            color: #1e1717;
            border: 2px solid #e6de08;
            background: transparent;
            transition: all 0.3s ease;
            box-shadow: 12px 15px 20px 0px rgba(46,61,73,0.15);
        }

.ads .ad-title h5 {
        text-transform: uppercase;
        font-size: 18px;
    }
</style>    
</div>
`,
});

Vvveb.Blocks.add("bootstrap4/login-form", {
	name: "Login form",
	image: "https://d2d3qesrx8xj6s.cloudfront.net/img/screenshots/fd3f41be24ffb976d66edf08adc4b2453a871b19.jpeg",
	html: `
<div class="container">
    <div class="row justify-content-md-center">
    
        <div class="col-sm-6 col-md-6 col-lg-6">
            <div class="account-wall">
                <div id="my-tab-content" class="tab-content">
						<div class="tab-pane active" id="login">
               		    <img class="profile-img img-fluid rounded-circle" src="https://source.unsplash.com/9UVmlIb0wJU/200x200"
                    alt="">
               			<form class="form-signin" action="" method="">
               				<input type="text" class="form-control" placeholder="Username" required autofocus>
               				<input type="password" class="form-control" placeholder="Password" required>
               				<input type="submit" class="btn btn-lg btn-default w-100" value="Sign In" />
               			</form>
               			<div id="tabs" data-tabs="tabs">
               				<p class="text-center"><a href="#register" data-toggle="tab">Need an Account?</a></p>
               				<p class="text-center"><a href="#select" data-toggle="tab">Select Account</a></p>
              				</div>
						</div>
						<div class="tab-pane" id="register">
							<form class="form-signin" action="" method="">
								<input type="text" class="form-control" placeholder="User Name ..." required autofocus>
								<input type="email" class="form-control" placeholder="Emaill Address ..." required>
								<input type="password" class="form-control" placeholder="Password ..." required>
								<input type="submit" class="btn btn-lg btn-default w-100" value="Sign Up" />
							</form>
							<div id="tabs" data-tabs="tabs">
               			<p class="text-center"><a href="#login" data-toggle="tab">Have an Account?</a></p>
              			</div>
						</div>
						<div class="tab-pane" id="select">
							<div id="tabs" data-tabs="tabs">
								<div class="media account-select">
									<a href="#user1" data-toggle="tab">
										<div class="pull-left">		
											<img class="select-img" src="https://source.unsplash.com/9UVmlIb0wJU/500x500"
                    alt="">
										</div>	 
										<div class="media-body">
											<h4 class="select-name">User Name 1</h4>
										</div>
									</a>
								</div>
                                <hr />
								<div class="media account-select">
									<a href="#user2" data-toggle="tab">
										<div class="pull-left">		
											<img class="select-img" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120"
                    alt="">
										</div>	 
										<div class="media-body">
											<h4 class="select-name">User Name 2</h4>
										</div>
									</a>
								</div>
                                <hr />
               			<p class="text-center"><a href="#login" data-toggle="tab">Back to Login</a></p>
              			</div>
						</div>
						<div class="tab-pane" id="user1">
							<img class="profile-img" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120"
                    alt="">
							<h3 class="text-center">User Name 1</h3>
							<form class="form-signin" action="" method="">
								<input type="hidden" class="form-control" value="User Name">
								<input type="password" class="form-control" placeholder="Password" autofocus required>
								<input type="submit" class="btn btn-lg btn-default w-100" value="Sign In" />
							</form>
							<p class="text-center"><a href="#login" data-toggle="tab">Back to Login</a></p>
               		<p class="text-center"><a href="#select" data-toggle="tab">Select another Account</a></p>
						</div>
						<div class="tab-pane" id="user2">
							<img class="profile-img" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120"
                    alt="">
							<h3 class="text-center">User Name 2</h3>
							<form class="form-signin" action="" method="">
								<input type="hidden" class="form-control" value="User Name">
								<input type="password" class="form-control" placeholder="Password" autofocus required>
								<input type="submit" class="btn btn-lg btn-default w-100" value="Sign In" />
							</form>
							<p class="text-center"><a href="#login" data-toggle="tab">Back to Login</a></p>
               		<p class="text-center"><a href="#select" data-toggle="tab">Select another Account</a></p>
						</div>
					</div>
            </div>
        </div>
    </div>
<style>
body{
    background-color:#f5f5f5;
}
.form-signin
{
    max-width: 330px;
    padding: 15px;
    margin: 0 auto;
}
.form-signin .form-control
{
    position: relative;
    font-size: 16px;
    height: auto;
    padding: 10px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
.form-signin .form-control:focus
{
    z-index: 2;
}
.form-signin input[type="text"]
{
    margin-bottom: -1px;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
}
.form-signin input[type="password"]
{
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}
.account-wall
{
    margin-top: 80px;
    padding: 40px 0px 20px 0px;
    background-color: #ffffff;
    box-shadow: 0 2px 10px 0 rgba(0, 0, 0, 0.16);
}
.login-title
{
    color: #555;
    font-size: 22px;
    font-weight: 400;
    display: block;
}
.profile-img
{
    width: 96px;
    height: 96px;
    margin: 0 auto 10px;
    display: block;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    border-radius: 50%;
}
.select-img
{
	border-radius: 50%;
    display: block;
    height: 75px;
    margin: 0 30px 10px;
    width: 75px;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    border-radius: 50%;
}
.select-name
{
    display: block;
    margin: 30px 10px 10px;
}

.logo-img
{
    width: 96px;
    height: 96px;
    margin: 0 auto 10px;
    display: block;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    border-radius: 50%;
}
</style>    
</div>
`,
});

Vvveb.Blocks.add("bootstrap4/about-team", {
	name: "About and Team Section",
	image: Vvveb.sectionsBaseUrl + "/screenshots/team/about-team.jpeg",
	html: `
<div class="container">
  <div class="row">
    <!-- Team Member 1 -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-0 shadow">
        <img src="https://source.unsplash.com/TMgQMXoglsM/500x350" class="card-img-top" alt="...">
        <div class="card-body text-center">
          <h5 class="card-title mb-0">Team Member</h5>
          <div class="card-text text-black-50">Web Developer</div>
        </div>
      </div>
    </div>
    <!-- Team Member 2 -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-0 shadow">
        <img src="https://source.unsplash.com/9UVmlIb0wJU/500x350" class="card-img-top" alt="...">
        <div class="card-body text-center">
          <h5 class="card-title mb-0">Team Member</h5>
          <div class="card-text text-black-50">Web Developer</div>
        </div>
      </div>
    </div>
    <!-- Team Member 3 -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-0 shadow">
        <img src="https://source.unsplash.com/sNut2MqSmds/500x350" class="card-img-top" alt="...">
        <div class="card-body text-center">
          <h5 class="card-title mb-0">Team Member</h5>
          <div class="card-text text-black-50">Web Developer</div>
        </div>
      </div>
    </div>
    <!-- Team Member 4 -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-0 shadow">
        <img src="https://source.unsplash.com/ZI6p3i9SbVU/500x350" class="card-img-top" alt="...">
        <div class="card-body text-center">
          <h5 class="card-title mb-0">Team Member</h5>
          <div class="card-text text-black-50">Web Developer</div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.row -->

</div>
`,
});

Vvveb.Blocks.add("bootstrap4/pricing-1", {
	name: "Pricing table",
	image: "https://d2d3qesrx8xj6s.cloudfront.net/img/screenshots/e92f797807bb4cd880bc3f161d9f9869854b6991.jpeg",
	html: `
<div id="plans">
  <div class="container">
	<div class="row">

		<!-- item -->
		<div class="col-md-4 text-center">
			<div class="panel panel-danger panel-pricing">
				<div class="panel-heading">
					<i class="fa fa-desktop"></i>
					<h3>Plan 1</h3>
				</div>
				<div class="panel-body text-center">
					<p><strong>$100 / Month</strong></p>
				</div>
				<ul class="list-group text-center">
					<li class="list-group-item"><i class="fa fa-check"></i> Personal use</li>
					<li class="list-group-item"><i class="fa fa-check"></i> Unlimited projects</li>
					<li class="list-group-item"><i class="fa fa-check"></i> 27/7 support</li>
				</ul>
				<div class="panel-footer">
					<a class="btn btn-lg w-100 btn-danger" href="#">BUY NOW!</a>
				</div>
			</div>
		</div>
		<!-- /item -->

		<!-- item -->
		<div class="col-md-4 text-center">
			<div class="panel panel-warning panel-pricing">
				<div class="panel-heading">
					<i class="fa fa-desktop"></i>
					<h3>Plan 2</h3>
				</div>
				<div class="panel-body text-center">
					<p><strong>$200 / Month</strong></p>
				</div>
				<ul class="list-group text-center">
					<li class="list-group-item"><i class="fa fa-check"></i> Personal use</li>
					<li class="list-group-item"><i class="fa fa-check"></i> Unlimited projects</li>
					<li class="list-group-item"><i class="fa fa-check"></i> 27/7 support</li>
				</ul>
				<div class="panel-footer">
					<a class="btn btn-lg w-100 btn-warning" href="#">BUY NOW!</a>
				</div>
			</div>
		</div>
		<!-- /item -->

		<!-- item -->
		<div class="col-md-4 text-center">
			<div class="panel panel-success panel-pricing">
				<div class="panel-heading">
					<i class="fa fa-desktop"></i>
					<h3>Plan 3</h3>
				</div>
				<div class="panel-body text-center">
					<p><strong>$300 / Month</strong></p>
				</div>
				<ul class="list-group text-center">
					<li class="list-group-item"><i class="fa fa-check"></i> Personal use</li>
					<li class="list-group-item"><i class="fa fa-check"></i> Unlimited projects</li>
					<li class="list-group-item"><i class="fa fa-check"></i> 27/7 support</li>
				</ul>
				<div class="panel-footer">
					<a class="btn btn-lg w-100 btn-success" href="#">BUY NOW!</a>
				</div>
			</div>
		</div>
		<!-- /item -->

		</div>
	</div>
<style>
@import url("http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css");

.panel-pricing {
  -moz-transition: all .3s ease;
  -o-transition: all .3s ease;
  -webkit-transition: all .3s ease;
}
.panel-pricing:hover {
  box-shadow: 0px 0px 30px rgba(0, 0, 0, 0.2);
}
.panel-pricing .panel-heading {
  padding: 20px 10px;
}
.panel-pricing .panel-heading .fa {
  margin-top: 10px;
  font-size: 58px;
}
.panel-pricing .list-group-item {
  color: #777777;
  border-bottom: 1px solid rgba(250, 250, 250, 0.5);
}
.panel-pricing .list-group-item:last-child {
  border-bottom-right-radius: 0px;
  border-bottom-left-radius: 0px;
}
.panel-pricing .list-group-item:first-child {
  border-top-right-radius: 0px;
  border-top-left-radius: 0px;
}
.panel-pricing .panel-body {
  background-color: #f0f0f0;
  font-size: 40px;
  color: #777777;
  padding: 20px;
  margin: 0px;
}
</style>
</div>
`,
});

Vvveb.Blocks.add("bootstrap4/loading-circle", {
	name: "Loading circle",
	image: "https://d2d3qesrx8xj6s.cloudfront.net/img/screenshots/39f0571b9a377cb7ac9c0c11d2346b07dabe1c66.png",
	html: `
<div class="loading-circle">
  <div class="loader">
    <div class="loader">
        <div class="loader">
           <div class="loader">

           </div>
        </div>
    </div>
  </div>
<style>  

.loading-circle {
	width: 150px;
    height: 150px;	
}

.loader {
    width: calc(100% - 0px);
	height: calc(100% - 0px);
	border: 8px solid #162534;
	border-top: 8px solid #09f;
	border-radius: 50%;
	animation: rotate 5s linear infinite;
}

@keyframes rotate {
100% {transform: rotate(360deg);}
} 
</style>  
</div> 
`,
});

Vvveb.Blocks.add("bootstrap4/block-quote", {
	name: "Block quote",
	image: "https://d2d3qesrx8xj6s.cloudfront.net/img/screenshots/d9f382e143b77d5a630dd79a2a3860611a8a953c.jpg",
	html: `
<div class="container">
    <blockquote class="quote-box">
      <p class="quotation-mark">
        “
      </p>
      <p class="quote-text">
        Don't believe anything that you read on the internet, it may be fake. 
      </p>
      <hr>
      <div class="blog-post-actions">
        <p class="blog-post-bottom pull-left">
          Abraham Lincoln
        </p>
        <p class="blog-post-bottom pull-right">
          <span class="badge quote-badge">896</span>  ❤
        </p>
      </div>
    </blockquote>
<style>
blockquote{
    border-left:none
}

.quote-badge{
    background-color: rgba(0, 0, 0, 0.2);   
}

.quote-box{
    
    overflow: hidden;
    margin-top: -50px;
    padding-top: -100px;
    border-radius: 17px;
    background-color: #4ADFCC;
    margin-top: 25px;
    color:white;
    width: 325px;
    box-shadow: 2px 2px 2px 2px #E0E0E0;
    
}

.quotation-mark{
    
    margin-top: -10px;
    font-weight: bold;
    font-size:100px;
    color:white;
    font-family: "Times New Roman", Georgia, Serif;
    
}

.quote-text{
    
    font-size: 19px;
    margin-top: -65px;
}
</style>
</div>
`,
});

Vvveb.Blocks.add("bootstrap4/subscribe-newsletter", {
	name: "Subscribe newsletter",
	image: "https://d2d3qesrx8xj6s.cloudfront.net/img/screenshots/4f610196b7cb9596555c9c8c475d93ab4ef084f6.jpg",
	html: `
<div class="subscribe-area pb-50 pt-70">
<div class="container">
	<div class="row">

					<div class="col-md-4">
						<div class="subscribe-text mb-15">
							<span>JOIN OUR NEWSLETTER</span>
							<h2>subscribe newsletter</h2>
						</div>
					</div>
					<div class="col-md-8">
						<div class="subscribe-wrapper subscribe2-wrapper mb-15">
							<div class="subscribe-form">
								<form action="#">
									<input placeholder="enter your email address" type="email">
									<button>subscribe <i class="fas fa-long-arrow-alt-right"></i></button>
								</form>
							</div>
						</div>
					</div>
				</div>

</div>
<style>
.subscribe-area {
background-image: linear-gradient(to top, #00c6fb 0%, #005bea 100%);
}

.pb-50 {
    padding-bottom: 50px;
}
.pt-70 {
    padding-top: 70px;
}

.mb-15 {
    margin-bottom: 15px;
}

.subscribe-text span {
    font-size: 12px;
    font-weight: 700;
    color: #fff;
    letter-spacing: 5px;
}
.subscribe-text h2 {
    color: #fff;
    font-size: 36px;
    font-weight: 300;
    margin-bottom: 0;
    margin-top: 6px;
}
.subscribe-wrapper {
    overflow: hidden;
}
.mb-15 {
    margin-bottom: 15px;
}
.subscribe-form {
}
.subscribe2-wrapper .subscribe-form input {
    background: none;
    border: 1px solid #fff;
    border-radius: 30px;
    color: #fff;
    display: inline-block;
    font-size: 15px;
    font-weight: 300;
    height: 57px;
    margin-right: 17px;
    padding-left: 35px;
    width: 70%;
    cursor: pointer;
}
 
.subscribe2-wrapper .subscribe-form button {
    background: #ffff;
    border: none;
    border-radius: 30px;
    color: #4b5d73;
    display: inline-block;
    font-size: 18px;
    font-weight: 400;
    line-height: 1;
    padding: 18px 46px;
    transition: all 0.3s ease 0s;
}
.subscribe2-wrapper .subscribe-form button i {
    font-size: 18px;
    padding-left: 5px;
}
</style>
</div>
`,
});

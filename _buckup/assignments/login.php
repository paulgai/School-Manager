<?php if (!isset($Translation)) {
	@header('Location: index.php?signIn=1');
	exit;
} ?>
<?php if (MULTI_TENANTS) redirect(SaaS::loginUrl(), true); ?>
<?php include_once(__DIR__ . '/header.php'); ?>

<?php if (Request::val('loginFailed')) { ?>
	<div class="alert alert-danger"><?php echo $Translation['login failed']; ?></div>
<?php } ?>

<style>
	.center-screen {
		position: absolute;
		top: 25%;
		left: 50%;
		transform: translate(-50%, -20%);
	}

	body {
		margin: auto;
		font-family: -apple-system, BlinkMacSystemFont, sans-serif;
		overflow: auto;
		background: linear-gradient(315deg, rgba(101, 50, 2000, 10) 3%, rgba(60, 230, 226, 1) 38%, rgba(48, 238, 226, 1) 68%, rgba(255, 193, 120, 10) 98%);
		animation: gradient 15s ease infinite;
		background-size: 400% 400%;
		background-attachment: fixed;
	}

	@keyframes gradient {
		0% {
			background-position: 0% 0%;
		}

		50% {
			background-position: 100% 100%;
		}

		100% {
			background-position: 0% 0%;
		}
	}

	.wave {
		background: rgb(255 255 255 / 25%);
		border-radius: 1000% 1000% 0 0;
		position: fixed;
		width: 200%;
		height: 12em;
		animation: wave 10s -3s linear infinite;
		transform: translate3d(0, 0, 0);
		opacity: 0.8;
		bottom: 0;
		left: 0;
		z-index: -1;
	}

	.wave:nth-of-type(2) {
		bottom: -1.25em;
		animation: wave 18s linear reverse infinite;
		opacity: 0.8;
	}

	.wave:nth-of-type(3) {
		bottom: -2.5em;
		animation: wave 20s -1s reverse infinite;
		opacity: 0.9;
	}

	@keyframes wave {
		2% {
			transform: translateX(1);
		}

		25% {
			transform: translateX(-25%);
		}

		50% {
			transform: translateX(-50%);
		}

		75% {
			transform: translateX(-25%);
		}

		100% {
			transform: translateX(1);
		}
	}
</style>

<div>
	<div class="wave"></div>
	<div class="wave"></div>
	<div class="wave"></div>
</div>
<div class="row">
	<div class="col-sm-6 col-lg-8" id="login_splash">
		<!-- customized splash content here -->
	</div>
	<div class="col-sm-12 col-lg-4 center-screen">
		<div class="panel panel-success">

			<div class="panel-heading">
				<h1 class="panel-title"><strong><?php echo $Translation['sign in here']; ?></strong></h1>
			</div>

			<div class="panel-body">
				<?php if (sqlValue("SELECT COUNT(1) from `membership_groups` WHERE `allowSignup`=1")) { ?>
					<a class="btn btn-success btn-lg pull-right" href="membership_signup.php"><?php echo $Translation['sign up']; ?></a>
					<div class="clearfix"></div>
				<?php } ?>
				<div class="row">
					<div class="col-sm-7">
						<form method="post" action="index.php">
							<div class="form-group">
								<label class="control-label" for="username"><?php echo $Translation['username']; ?></label>
								<input class="form-control" name="username" id="username" type="text" placeholder="<?php echo $Translation['username']; ?>" required>
							</div>
							<div class="form-group">
								<label class="control-label" for="password"><?php echo $Translation['password']; ?></label>
								<input class="form-control" name="password" id="password" type="password" placeholder="<?php echo $Translation['password']; ?>" required>
								<span class="help-block"><?php echo $Translation['forgot password']; ?></span>
							</div>
							<div class="checkbox">
								<label class="control-label" for="rememberMe">
									<input type="checkbox" name="rememberMe" id="rememberMe" value="1">
									<?php echo $Translation['remember me']; ?>
								</label>
							</div>

							<div class="row">
								<div class="col-sm-offset-3 col-sm-6">
									<button name="signIn" type="submit" id="submit" value="signIn" class="btn btn-primary btn-lg btn-block"><?php echo $Translation['sign in']; ?></button>
								</div>
							</div>
						</form>
					</div>
					<div class="col-sm-5">
						<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
						<lottie-player src="https://assets7.lottiefiles.com/packages/lf20_n2yhd0lo.json" mode="bounce" background="transparent" speed="1" style="width: auto; height: auto;" loop autoplay></lottie-player>
					</div>
				</div>
			</div>

			<?php if (is_array(getTableList()) && count(getTableList())) { /* if anon. users can see any tables ... */ ?>
				<div class="panel-footer">
					<a href="index.php"><i class="glyphicon glyphicon-user text-muted"></i> <?php echo $Translation['continue browsing as guest']; ?></a>
				</div>
			<?php } ?>

		</div>
	</div>
</div>

<script>
	document.getElementById('username').focus();
</script>
<?php include_once(__DIR__ . '/footer.php');

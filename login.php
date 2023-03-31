<div class="container-fluid">
        <div class="row">
            <div class="col-2 my-4">
                <?php if (isset($_SESSION['loggedin'])) : ?>
                    <div class="container border">
                        <h4 class="col mx-auto text-center" id="user">Well met <?= $_SESSION['user'] ?>!</h4>
                        <p class="my-1"><a href="member.php?userId=<?=$_SESSION['userId'] ?>" class="text-decoration-none">My Account</a></p>
                        <p><a href="logout.php" class="text-decoration-none">Logout</a></p>
                    </div>
                </form>
                <?php else : ?>
                    <div class="container border">
                        <div class="col me-4 my-2">
                            <form action="loginpage.php" method="post">
                                <div class="row">
                                    <label class="col-sm-1 me-auto col-form-label" for="username">Username:</label>
                                    <div class="col my-1">
                                        <input class="col" type="text" name="userName" id="userName">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label class="col-sm-1 me-auto col-form-label" for="password">Password:</label>
                                    <div class="col my-1">
                                        <input class="col" type="password" name="password" id="password">
                                    </div>
                                </div>
                                <button class="btn btn-outline-success my-1" type="submit">Login</button>
                            </form>
                            <p class="text-center my-3 mx-auto">No account? <a href="register.php" class="text-decoration-none">Register now!</a></p>
                        </div>
                    </div>
                <?php endif ?>
            </div>   

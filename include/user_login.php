<main>
        <div class="inner_container">
            <div class="register">
                <form action="scripts/register.php" method="post">
                    <label for="email">Email</label>
                    <input name="login" type="email" id="email" required>
                    <label for="nickname">Ник</label>
                    <input name="name" type="text" id="nickname" required>
                    <label for="password">Пароль</label>
                    <input name="password" type="password" id="password" required>
                    <label>
                        <input type="checkbox" required>
                        <span>Я принимаю условия Пользовательского соглашения</span>
                    </label>
                    <button>Зарегистрироваться</button>
                </form>
                <div class="switch">
                    <p>Уже зарегистрированы?</p>
                    <button id="switch_to_login">Войдите</button>
                </div>
            </div>
            <div class="login active">
                <form action="scripts/login.php" method="post">
                    <label for="login_email">Email</label>
                    <input name="login" type="email" id="login_email">
                    <label for="login_password">Пароль</label>
                    <input name="password" type="password" id="login_password">
                    <button>Войти</button>
                </form>
                <div class="switch">
                    <p>Ещё нет аккаунта?</p>
                    <button id="switch_to_register">Зарегистрируйтесь</button>
                </div>
            </div>
        </div>
    </main>
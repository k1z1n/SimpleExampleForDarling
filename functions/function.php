<?php if (isset($_SESSION['errors'])) : ?>
    <!-- Проверка наличия ошибок в сессии -->

    <?php foreach ($_SESSION['errors'] as $err) : ?>
        <!-- Перебор ошибок в цикле -->

        <div class="">
            <!-- Вывод каждой ошибки в блоке div -->
            <?= $err; ?>
        </div>

    <?php endforeach; ?>

    <?php unset($_SESSION['errors']); ?>
    <!-- Очистка массива ошибок из сессии -->

<?php endif; ?>

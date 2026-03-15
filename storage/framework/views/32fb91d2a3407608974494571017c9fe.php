<tr>
    <td class="header">
        <a href="<?php echo new \Illuminate\Support\EncodedHtmlString($url); ?>" style="display: inline-block;">
            <?php if(trim($slot) === 'Laravel'): ?>
                
                <img src="<?php echo new \Illuminate\Support\EncodedHtmlString(asset('img/logo/sip.png')); ?>" alt="" width="70" height="70"
                    class="d-inline-block align-text-top">
            <?php else: ?>
                <?php echo new \Illuminate\Support\EncodedHtmlString($slot); ?>

            <?php endif; ?>
        </a>
    </td>
</tr>
<?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/vendor/mail/html/header.blade.php ENDPATH**/ ?>
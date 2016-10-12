<?php
IncludeModuleLangFile(__FILE__);
?>
<script>
    $(document).ready(function () {
        function getWeekDay(date) {
            var days = [
                '<?= GetMessage('SUNDAY') ?>',
                '<?= GetMessage('MONDAY') ?>',
                '<?= GetMessage('TUESDAY') ?>',
                '<?= GetMessage('WEDNESDAY') ?>',
                '<?= GetMessage('THURSDAY') ?>',
                '<?= GetMessage('FRIDAY') ?>',
                '<?= GetMessage('SATURDAY') ?>'
            ];

            return days[date.getDay()];
        }

        function getMonth(date) {
            var months = [
                '<?= GetMessage('JANUARY') ?>',
                '<?= GetMessage('FEBRUARY') ?>',
                '<?= GetMessage('MARCH') ?>',
                '<?= GetMessage('APRIL') ?>',
                '<?= GetMessage('MAY') ?>',
                '<?= GetMessage('JUNE') ?>',
                '<?= GetMessage('JULE') ?>',
                '<?= GetMessage('AUGUST') ?>',
                '<?= GetMessage('SEPTEMBER') ?>',
                '<?= GetMessage('OCTOBER') ?>',
                '<?= GetMessage('NOVEMBER') ?>',
                '<?= GetMessage('DECEMBER') ?>'
            ];

            return months[date.getMonth()];
        }

        var date = new Date();

        $('.b-widget .time').text(getWeekDay(date) + ", " + date.getDate() + " " + getMonth(date));
    });
</script>
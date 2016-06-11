
<body>
<script type="text/javascript">
// <![CDATA[(
(function (tos) {
  window.setInterval(function () {
    tos = (function (call) {
      return t[0] == 50 ? (parseInt(t[1]) + 1) + ':00' : (t[1] || '0') + ':' + (parseInt(t[0]) + 10);
    })(tos.split(':').reverse());
    window.pageTracker ? pageTracker._trackEvent('Time', 'Log', tos) : _gaq.push(['_trackEvent', 'Time', 'Log', tos]);
  }, 10000);
})('00');
// ]]>
</script>
</body>
<?php echo $footer; ?>
<script type="text/javascript" src="jumptap tracking script location"></script>
<script type="text/javascript">
data = new JTData({pID: '1e2a34a8', uID: '1234567890', rtkw: 'homepagevisit'});
if (typeof JTApi === 'object' && typeof JTApi.traffic === 'function') {
   JTApi.traffic(data);
}
</script>

<?php ?>

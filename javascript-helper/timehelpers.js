const TimeHelpers = function () {

  let self = this;

  this.carbon = function( timestamp ) {
      return new Date( timestamp );
  },

  this.to_time_into_minutes = function (time) {

      let split_total_start_work_time = time.split(':')
      
      return (+split_total_start_work_time[0]) * 60 + (+split_total_start_work_time[1]);
  },

  this.getTimerTotalWorkHoursByWorkTimerObject = function( work_status_timer ) {

      let on_start_work           = self.carbon( work_status_timer.start_work_timer );
      let on_work_diff_minutes    = 0;

      if( work_status_timer.total_work_timer && work_status_timer.total_work_timer != '00:00:00')
      {
          // Formula:2
          let difference_between_two_datetime = self.to_difference_between_two_datetime( on_start_work, new Date() );
          let difference_datetime             = (
              difference_between_two_datetime.hours +':'+
              difference_between_two_datetime.minutes+':'+
              '00'
          );

          on_work_diff_minutes = self.to_time_into_minutes(
              self.addTimes(work_status_timer.total_work_timer, difference_datetime)
          )
      }
      else
      {
          // Formula:1
          on_work_diff_minutes = Math.floor( ((new Date().getTime() - on_start_work.getTime())/(1000*60)) );
          // console.log( 'formula 1')
      }

      return on_work_diff_minutes;
  },

  /**
  * Add two string time values (HH:mm:ss) with javascript
  *
  * Usage:
  *  > addTimes('04:20:10', '21:15:10');
  *  > "25:35:20"
  *  > addTimes('04:35:10', '21:35:10');
  *  > "26:10:20"
  *  > addTimes('30:59', '17:10');
  *  > "48:09:00"
  *  > addTimes('19:30:00', '00:30:00');
  *  > "20:00:00"
  *
  * @param {String} startTime  String time format
  * @param {String} endTime  String time format
  * @returns {String}
  */
  this.addTimes = function (startTime, endTime) {
   
      var times = [ 0, 0, 0 ]
      var max = times.length

      var a = (startTime || '').split(':')
      var b = (endTime || '').split(':')

      // normalize time values
      for (var i = 0; i < max; i++) {
          a[i] = isNaN(parseInt(a[i])) ? 0 : parseInt(a[i])
          b[i] = isNaN(parseInt(b[i])) ? 0 : parseInt(b[i])
      }

      // store time values
      for (var i = 0; i < max; i++) {
          times[i] = a[i] + b[i]
      }

      var hours = times[0]
      var minutes = times[1]
      var seconds = times[2]

      if (seconds >= 60) {
          var m = (seconds / 60) << 0
          minutes += m
          seconds -= 60 * m
      }

      if (minutes >= 60) {
          var h = (minutes / 60) << 0
          hours += h
          minutes -= 60 * h
      }

      return ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2) + ':' + ('0' + seconds).slice(-2)
  }

  /**
   * Count-Down Timer for Increment
   * - - - - - - - - - - - - - - 
   * @param  displayInstance [description]
   * @param  duration        [description]
   * @param  renderType      [html | input ]
   *
   *
   * @usage:
   * - - - - - - - -
   * let on_start_work           = mis_obj_class.carbon( '2018-07-19 15:20:00' );
   * let on_work_diff_minutes    = Math.floor( ((new Date().getTime() - on_start_work.getTime())/(1000*60)) );
   * 
   * if( on_work_diff_minutes > 0 ) {
   *     MisCustomHelper.renderCountDownTimerIncrement( $('#startwork'), on_work_diff_minutes, 'html' );
   * }
   * 
   */
  this.renderCountDownTimerIncrement = function ( displayInstance, duration, renderType ) 
  {
      let upgradeTime = (duration*60);
      let seconds     = upgradeTime;
      renderType      = (renderType || 'html');
      
      setInterval( function() {

          let days                = Math.floor(seconds/24/60/60);
          let hoursLeft           = Math.floor((seconds) - (days*86400));
          let hours               = Math.floor(hoursLeft/3600);
          let minutesLeft         = Math.floor((hoursLeft) - (hours*3600));
          let minutes             = Math.floor(minutesLeft/60);
          let remainingSeconds    = (seconds % 60);

          // remainingSeconds = remainingSeconds * (-1);

          if(days > 0)
              hours = hours + (days * 24);

          displayInstance[renderType](
              // (days < 10 ? "0" + days : days) + ":" +
              (hours < 10 ? "0" + hours : hours) + ":" +
              (minutes < 10 ? "0" + minutes : minutes) + ":" +
              (remainingSeconds < 10 ? "0" + remainingSeconds : remainingSeconds)
          );

          seconds++;

      }, 1000 ); //(1000*60)
  }

  /**
   * Count-Down Timer for Decrement
   * - - - - - - - - - - - - - - - - - -
   * @param  displayInstance [description]
   * @param  duration        [description]
   * @param  renderType      [html | input ]
   * 
   */
  this.renderCountDownTimer = function ( displayInstance, duration, renderType ) 
  {
      let upgradeTime = (duration*60);
      let seconds     = upgradeTime;
      renderType      = (renderType || 'html');
      
      setInterval( function() {

          let days                = Math.floor(seconds/24/60/60);
          let hoursLeft           = Math.floor((seconds) - (days*86400));
          let hours               = Math.floor(hoursLeft/3600);
          let minutesLeft         = Math.floor((hoursLeft) - (hours*3600));
          let minutes             = Math.floor(minutesLeft/60);
          let remainingSeconds    = (seconds % 60);

          if(days > 0)
              hours = hours + (days * 24);

          displayInstance[renderType](
              // (days < 10 ? "0" + days : days) + ":" +
              (hours < 10 ? "0" + hours : hours) + ":" +
              (minutes < 10 ? "0" + minutes : minutes) + ":" +
              (remainingSeconds < 10 ? "0" + remainingSeconds : remainingSeconds)
          );

          if (seconds == 0) {
              clearInterval( this.renderCountDownTimer() );
              displayInstance[renderType]('00:00');
              return;
          } else {
              seconds--;
          }

      }, 1000 ); //(1000*60)
  }   
}
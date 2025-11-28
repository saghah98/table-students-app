// frontend/script.js
$(function(){

  const API = '../backend'; // if frontend folder is inside project: adjust if needed

  // NAVIGATION
  function showSection(id){
    $('#loginSection, #attendanceSection, #addSection, #reportSection').hide();
    $(id).show();
  }

  // simple auth state (not production safe)
  function setLoggedIn(logged){
    if(logged){
      $('#nav-logout').show();
      $('#loginSection').hide();
      showSection('#attendanceSection');
    } else {
      $('#nav-logout').hide();
      showSection('#loginSection');
    }
  }

  // initial
  setLoggedIn(false);

  $('#nav-home').click(()=> showSection('#loginSection'));
  $('#nav-att').click(()=> showSection('#attendanceSection'));
  $('#nav-add').click(()=> showSection('#addSection'));
  $('#nav-report').click(()=> showSection('#reportSection'));
  $('#nav-logout').click(()=> {
    // simple client side logout
    setLoggedIn(false);
    $('#loginMsg').text('Logged out.');
  });

  /* ------------------ LOGIN ------------------ */
  $('#loginForm').submit(function(e){
    e.preventDefault();
    $.post(API + '/login.php', $(this).serialize(), function(res){
      if(typeof res === 'string') res = JSON.parse(res);
      if(res.status === 'success'){
        $('#loginMsg').text('Welcome!');
        setLoggedIn(true);
        loadStudents();
        loadAttendance();
      } else {
        $('#loginMsg').text(res.message || 'Login failed');
      }
    }).fail(function(){ $('#loginMsg').text('Server error'); });
  });

  /* ------------------ STUDENTS CRUD ------------------ */
  function loadStudents(){
    $.get(API + '/list_students.php', function(res){
      if(typeof res === 'string') res = JSON.parse(res);
      const tbody = $('#studentsTable tbody').empty();
      res.forEach(s => {
        const row = $('<tr>');
        row.append(`<td>${s.id}</td>`);
        row.append(`<td>${s.fullname}</td>`);
        row.append(`<td>${s.matricule}</td>`);
        row.append(`<td>${s.group_id}</td>`);
        row.append(`<td>
          <button class="actions-btn edit-btn" data-id="${s.id}">Edit</button>
          <button class="actions-btn del-btn" data-id="${s.id}">Delete</button>
        </td>`);
        tbody.append(row);
      });
    });
  }

  $('#studentForm').submit(function(e){
    e.preventDefault();
    $.post(API + '/add_student.php', $(this).serialize(), function(res){
      if(typeof res === 'string') res = JSON.parse(res);
      $('#addMsg').text(res.message);
      if(res.status === 'success'){
        $('#studentForm')[0].reset();
        loadStudents();
        loadAttendance(); // reload attendance table to include new student
      }
    }).fail(()=> $('#addMsg').text('Server error'));
  });

  // delegate edit / delete in students table
  $('#studentsTable').on('click', '.del-btn', function(){
    if(!confirm('Delete student?')) return;
    const id = $(this).data('id');
    $.post(API + '/delete_student.php', {id}, function(res){
      if(typeof res === 'string') res = JSON.parse(res);
      alert(res.message);
      loadStudents();
      loadAttendance();
    });
  });

  $('#studentsTable').on('click', '.edit-btn', function(){
    const id = $(this).data('id');
    // simple prompt-based edit (quick)
    const fullname = prompt('Full name?');
    const matricule = prompt('Matricule?');
    const group = prompt('Group?');
    if(fullname && matricule && group){
      $.post(API + '/update_student.php', {id, fullname, matricule, group_id: group}, function(res){
        if(typeof res === 'string') res = JSON.parse(res);
        alert(res.message);
        loadStudents();
        loadAttendance();
      });
    }
  });

  /* ------------------ ATTENDANCE TABLE UI & SAVE ------------------ */

  // build attendance table rows from students list + states
  function loadAttendance(){
    $.get(API + '/list_students.php', function(res){
      if(typeof res === 'string') res = JSON.parse(res);
      const tbody = $('#attendanceTable tbody').empty();
      res.forEach(student => {
        const tr = $('<tr>').attr('data-student-id', student.id);
        // split fullname into last/first if possible:
        const names = (student.fullname||'').split(' ');
        const last = names[0] || '';
        const first = names.slice(1).join(' ') || '';
        tr.append(`<td>${last}</td>`);
        tr.append(`<td>${first}</td>`);

        // 6 sessions, each has P and Pa
        for(let session=1; session<=6; session++){
          tr.append(`<td><input type="checkbox" class="present" data-session="${session}"></td>`);
          tr.append(`<td><input type="checkbox" class="participated" data-session="${session}"></td>`);
        }

        tr.append('<td class="message"></td>');
        tr.append(`<td><button class="save-row" data-id="${student.id}">Save</button></td>`);
        tbody.append(tr);
      });

      // after building rows, fetch existing attendance and apply checkboxes:
      $.get(API + '/attendance.php?action=get_all', function(attRes){
        if(typeof attRes === 'string') attRes = JSON.parse(attRes);
        // attRes should be an array {student_id, session, present, participated}
        attRes.forEach(rec => {
          const row = $('#attendanceTable tbody tr[data-student-id="'+rec.student_id+'"]');
          if(row.length){
            row.find(`.present[data-session="${rec.session}"]`).prop('checked', rec.present == 1);
            row.find(`.participated[data-session="${rec.session}"]`).prop('checked', rec.participated == 1);
          }
        });
        updateTableUI();
      });

    });
  }

  // when a present toggled, uncheck corresponding participated in same column? keep both independent.
  $('#attendanceTable').on('change', 'input.present', function(){
    const $row = $(this).closest('tr');
    updateTableRow($row);
  });
  $('#attendanceTable').on('change', 'input.participated', function(){
    const $row = $(this).closest('tr');
    updateTableRow($row);
  });

  // Save single row (student) to backend
  $('#attendanceTable').on('click', '.save-row', function(){
    const studentId = $(this).data('id');
    const row = $('#attendanceTable tbody tr[data-student-id="'+studentId+'"]');
    const payload = [];
    row.find('input.present,input.participated').each(function(){
      const session = $(this).data('session');
      const present = $(this).hasClass('present') ? $(this).is(':checked') : null;
      const participated = $(this).hasClass('participated') ? $(this).is(':checked') : null;
      // we will insert/update both present & participated per session
      // only push once per session: gather values:
    });

    // Build payload sessions
    for(let s=1; s<=6; s++){
      const present = row.find(`.present[data-session="${s}"]`).is(':checked') ? 1 : 0;
      const participated = row.find(`.participated[data-session="${s}"]`).is(':checked') ? 1 : 0;
      payload.push({student_id: studentId, session: s, present, participated});
    }

    $.ajax({
      url: API + '/attendance.php',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({action:'save_many', records: payload}),
      success: function(res){
        if(typeof res === 'string') res = JSON.parse(res);
        alert(res.message);
        loadAttendance(); // refresh messages/colors
      },
      error: function(){ alert('Server error saving attendance'); }
    });
  });

  // highlight excellent students
  $('#highlightBtn').click(function(){
    $('#attendanceTable tbody tr').each(function(){
      const absences = $(this).find('input.present:not(:checked)').length / 1; // number of unchecked present across 6
      if(absences < 3) $(this).css('background-color','#b2ffb2').animate({opacity:0.6},200).animate({opacity:1},200);
    });
  });

  $('#resetBtn').click(function(){ $('#attendanceTable tbody tr').css('background-color',''); });

  // Update message cell and add classes
  function updateTableRow($row){
    let participations = 0, absences = 0;
    $row.find('input.present').each(function(){
      if($(this).is(':checked')) participations++;
      else absences++;
    });
    const msgCell = $row.find('.message');
    $row.removeClass('good warning bad');
    if(absences < 3){
      $row.addClass('good');
      msgCell.text(`Good attendance (${absences} abs, ${participations} par)`);
    } else if(absences >=3 && absences <=4){
      $row.addClass('warning');
      msgCell.text(`Warning – Low attendance (${absences} abs, ${participations} par)`);
    } else {
      $row.addClass('bad');
      msgCell.text(`Excluded – Too many absences (${absences} abs, ${participations} par)`);
    }
  }

  function updateTableUI(){
    $('#attendanceTable tbody tr').each(function(){ updateTableRow($(this)); });
  }

  /* ------------------ REPORT ------------------ */
  $('#showReportBtn').click(function(){
    // compute locally from attendance table
    const rows = $('#attendanceTable tbody tr');
    const total = rows.length;
    let presentCount = 0, participatedCount = 0;
    rows.each(function(){
      const anyPresent = $(this).find('input.present:checked').length > 0;
      const anyParticip = $(this).find('input.participated:checked').length > 0;
      if(anyPresent) presentCount++;
      if(anyParticip) participatedCount++;
    });

    $('#totalStudents').text(total);
    $('#studentsPresent').text(presentCount);
    $('#studentsParticipated').text(participatedCount);
    $('#reportContent').show();

    const ctx = $('#attendanceChart');
    if(window.attChart) window.attChart.destroy();
    window.attChart = new Chart(ctx, {
      type:'bar',
      data:{
        labels:['Total','Present','Participated'],
        datasets:[{label:'Attendance Overview', data:[total,presentCount,participatedCount]}]
      },
      options:{responsive:true,scales:{y:{beginAtZero:true,ticks:{stepSize:1}}}}
    });
  });

});

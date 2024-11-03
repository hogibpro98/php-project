function generate() {
  const tasks = [];
  for (let i = 0; i < 20; i++) {
    let startTime = -1;
    let duration = 0.5;
    for (let j = 0; j < 10; j++) {
      if (Math.random() * 10 > 5) {
        startTime += 0.5 + duration;
      } else {
        startTime += 1 + duration;
      }

      if (Math.random() * 10 > 5) {
        startTime -= duration;

        startTime = Math.max(0, startTime);
      }

      if (startTime > 23) {
        break;
      }

      duration =
        Math.ceil(Math.random() * 2) + (Math.random() * 10 > 5 ? 0 : 0.5);

      duration -= startTime + duration > 24 ? startTime + duration - 24 : 0;

      const task = {
        startTime: startTime,
        duration: duration,
        column: i,
        id: Math.ceil(Math.random() * 100000),
        title: "Service " + i + " " + j,
      };

      tasks.push(task);
    }
  }

  console.log("tasks count: " + tasks.length);

  console.log(JSON.stringify(tasks));

  $("#skeduler-container").skeduler({
    headers: [
      "Specialist 1",
      "Specialist 2",
      "Specialist 3",
      "Specialist 4",
      "Specialist 5",
      "Specialist 6",
      "Specialist 7",
      "Specialist 8",
      "Specialist 9",
      "Specialist 10",
    ],
    tasks: tasks,
    cardTemplate: '<div>${id}</div><div>${title}</div>',
    onClick: function (e, t) {
      console.log(e, t);
    },
  });
}

$(function () {
  $('.btn_prev_mon').click(function () {
    // 本日を作成.
    const PrevMonFrom = new Date();
    const PrevMonTo = new Date();

    // 日付に1を設定します.
    PrevMonFrom.setDate(1);

    // 1ヶ月加えて翌月
    PrevMonTo.setMonth(PrevMonTo.getMonth() + 1);
    // 日付に0を設定し、該当月の月末
    PrevMonTo.setDate(0);

    const year1 = PrevMonFrom.getFullYear();
    const month1 = PrevMonFrom.getMonth() + 1;
    const date1 = PrevMonFrom.getDate();

    const year2 = PrevMonTo.getFullYear();
    const month2 = PrevMonTo.getMonth() + 1;
    const date2 = PrevMonTo.getDate();

    const fromDate =
        ("0000" + year1).slice(-4) +
        "-" +
        ("00" + month1).slice(-2) +
        "-" +
        ("00" + date1).slice(-2);
    const toDate =
        ("0000" + year2).slice(-4) +
        "-" +
        ("00" + month2).slice(-2) +
        "-" +
        ("00" + date2).slice(-2);
    $('.month_from').val(fromDate);
    $('.month_to').val(toDate);
  });
  $('.btn_next_mon').click(function () {
    // 本日を作成.
    const PrevMonFrom = new Date();
    const PrevMonTo = new Date();
    // 1ヶ月加えて翌月
    PrevMonFrom.setMonth(PrevMonFrom.getMonth() + 1);
    // 日付に1を設定します.
    PrevMonFrom.setDate(1);

    // 1ヶ月加えて翌月
    PrevMonTo.setMonth(PrevMonTo.getMonth() + 2);
    // 日付に0を設定し、該当月の月末
    PrevMonTo.setDate(0);

    const year1 = PrevMonFrom.getFullYear();
    const month1 = PrevMonFrom.getMonth() + 1;
    const date1 = PrevMonFrom.getDate();

    const year2 = PrevMonTo.getFullYear();
    const month2 = PrevMonTo.getMonth() + 1;
    const date2 = PrevMonTo.getDate();

    const fromDate =
        ("0000" + year1).slice(-4) +
        "-" +
        ("00" + month1).slice(-2) +
        "-" +
        ("00" + date1).slice(-2);
    const toDate =
        ("0000" + year2).slice(-4) +
        "-" +
        ("00" + month2).slice(-2) +
        "-" +
        ("00" + date2).slice(-2);
    $('.month_from').val(fromDate);
    $('.month_to').val(toDate);
  });
});
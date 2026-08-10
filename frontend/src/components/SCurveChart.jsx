function SCurveChart({ data = [], actualData = [] }) {
  if (!data.length && !actualData.length) {
    return <p>ยังไม่มีข้อมูล S-Curve</p>;
  }

  const width = 800;
  const height = 360;

  const padding = {
    top: 40,
    right: 30,
    bottom: 65,
    left: 60,
  };

  const chartWidth = width - padding.left - padding.right;
  const chartHeight = height - padding.top - padding.bottom;

  const maxValue = 100;

  /*
   * รวมวันที่ของ Planned และ Actual
   * เพื่อให้ทั้งสองเส้นใช้แกน X เดียวกัน
   */
  const allDates = [
    ...data.map((item) => item.date),
    ...actualData.map((item) => item.date),
  ];

  const uniqueDates = [...new Set(allDates)].sort(
    (a, b) => new Date(a) - new Date(b)
  );

  const minDate = uniqueDates.length
    ? new Date(uniqueDates[0]).getTime()
    : 0;

  const maxDate = uniqueDates.length
    ? new Date(
        uniqueDates[uniqueDates.length - 1]
      ).getTime()
    : 0;

  const dateRange = Math.max(maxDate - minDate, 1);

  const getX = (date) => {
    const timestamp = new Date(date).getTime();

    return (
      padding.left +
      ((timestamp - minDate) / dateRange) * chartWidth
    );
  };

  const getY = (value) => {
    return (
      padding.top +
      chartHeight -
      (Number(value) / maxValue) * chartHeight
    );
  };

  /*
   * Planned points
   */
  const plannedPoints = data.map((item) => ({
    ...item,
    x: getX(item.date),
    y: getY(item.planned),
  }));

  /*
   * Actual points
   */
  const actualPoints = actualData.map((item) => ({
    ...item,
    x: getX(item.date),
    y: getY(item.actual),
  }));

  /*
   * SVG Path
   */
  const createPath = (points) => {
    return points
      .map(
        (point, index) =>
          `${index === 0 ? "M" : "L"} ${point.x} ${point.y}`
      )
      .join(" ");
  };

  const plannedPath = createPath(plannedPoints);
  const actualPath = createPath(actualPoints);

  return (
    <div>
      {/* Legend */}
      <div
        style={{
          display: "flex",
          gap: "24px",
          marginBottom: "12px",
          alignItems: "center",
          fontSize: "14px",
        }}
      >
        <div
          style={{
            display: "flex",
            alignItems: "center",
            gap: "7px",
          }}
        >
          <span
            style={{
              width: "12px",
              height: "12px",
              borderRadius: "50%",
              backgroundColor: "#2563eb",
              display: "inline-block",
            }}
          />

          <span>Planned</span>
        </div>

        <div
          style={{
            display: "flex",
            alignItems: "center",
            gap: "7px",
          }}
        >
          <span
            style={{
              width: "12px",
              height: "12px",
              borderRadius: "50%",
              backgroundColor: "#16a34a",
              display: "inline-block",
            }}
          />

          <span>Actual</span>
        </div>
      </div>

      <svg
        viewBox={`0 0 ${width} ${height}`}
        width="100%"
        role="img"
        aria-label="Project S-Curve"
      >
        {/* Horizontal grid */}
        {[0, 25, 50, 75, 100].map((value) => {
          const y = getY(value);

          return (
            <g key={value}>
              <line
                x1={padding.left}
                y1={y}
                x2={width - padding.right}
                y2={y}
                stroke="#e5e7eb"
              />

              <text
                x={padding.left - 10}
                y={y + 5}
                textAnchor="end"
                fontSize="12"
                fill="#6b7280"
              >
                {value}%
              </text>
            </g>
          );
        })}

        {/* Planned line */}
        {plannedPoints.length > 0 && (
          <path
            d={plannedPath}
            fill="none"
            stroke="#2563eb"
            strokeWidth="4"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
        )}

        {/* Actual line */}
        {actualPoints.length > 0 && (
          <path
            d={actualPath}
            fill="none"
            stroke="#16a34a"
            strokeWidth="4"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
        )}

        {/* Planned points */}
        {plannedPoints.map((point) => (
          <g key={`planned-${point.date}`}>
            <circle
              cx={point.x}
              cy={point.y}
              r="6"
              fill="#ffffff"
              stroke="#2563eb"
              strokeWidth="3"
            />

            <text
              x={point.x}
              y={point.y - 12}
              textAnchor="middle"
              fontSize="12"
              fontWeight="600"
              fill="#2563eb"
            >
              {point.planned}%
            </text>
          </g>
        ))}

        {/* Actual points */}
        {actualPoints.map((point) => (
          <g key={`actual-${point.date}`}>
            <circle
              cx={point.x}
              cy={point.y}
              r="6"
              fill="#ffffff"
              stroke="#16a34a"
              strokeWidth="3"
            />

            <text
              x={point.x}
              y={point.y - 12}
              textAnchor="middle"
              fontSize="12"
              fontWeight="600"
              fill="#16a34a"
            >
              {point.actual}%
            </text>
          </g>
        ))}
{/* X-axis dates */}
{uniqueDates.map((date, index) => {
  const x = getX(date);

  // แสดงเฉพาะบางวันที่เมื่อวันที่อยู่ใกล้กันมาก
  const previousDate = uniqueDates[index - 1];
  const previousX = previousDate ? getX(previousDate) : null;

  const isFirst = index === 0;
  const isLast = index === uniqueDates.length - 1;
  const isFarEnough =
    previousX === null || x - previousX >= 70;

  if (!isFirst && !isLast && !isFarEnough) {
    return null;
  }

  const formattedDate = new Intl.DateTimeFormat("th-TH", {
    day: "numeric",
    month: "short",
  }).format(new Date(date));

  return (
    <text
      key={`date-${date}`}
      x={x}
      y={height - 25}
      textAnchor="middle"
      fontSize="11"
      fill="#6b7280"
    >
      {formattedDate}
    </text>
  );
})}
      </svg>
    </div>
  );
}

export default SCurveChart;

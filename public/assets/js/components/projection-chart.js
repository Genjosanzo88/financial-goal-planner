class ProjectionChart extends HTMLElement {
    connectedCallback() {
        document.addEventListener(
            'financial-plan-created',
            (event) => this.render(event.detail)
        );
    }

    render(plan) {
        const width = 900;
        const height = 340;

        const padding = {
            top: 30,
            right: 30,
            bottom: 50,
            left: 70
        };

        const allValues = plan.scenarios.flatMap(
            (scenario) =>
                scenario.timeline.map(
                    (point) => point.capital
                )
        );

        const maximum =
            Math.max(
                plan.targetAmount,
                ...allValues
            ) * 1.1;

        const chartWidth =
            width - padding.left - padding.right;

        const chartHeight =
            height - padding.top - padding.bottom;

        const x = (year) =>
            padding.left
            + (year / plan.years) * chartWidth;

        const y = (capital) =>
            padding.top
            + (1 - capital / maximum) * chartHeight;

        const paths = plan.scenarios
            .map((scenario, index) => {
                const points = scenario.timeline
                    .map(
                        (point) =>
                            `${x(point.year)},${y(point.capital)}`
                    )
                    .join(' ');

                return `
                    <polyline
                        points="${points}"
                        class="projection-line projection-line-${index}"
                        fill="none"
                        stroke-width="3"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                `;
            })
            .join('');

        const ticks = [];

        for (let year = 0; year <= plan.years; year += 5) {
            ticks.push(`
                <text
                    x="${x(year)}"
                    y="${height - 18}"
                    text-anchor="middle"
                    class="chart-label"
                >
                    ${year}
                </text>
            `);
        }

        if (plan.years % 5 !== 0) {
            ticks.push(`
                <text
                    x="${x(plan.years)}"
                    y="${height - 18}"
                    text-anchor="middle"
                    class="chart-label"
                >
                    ${plan.years}
                </text>
            `);
        }

        this.innerHTML = `
            <section class="card">

                <div class="chart-header">
                    <div>
                        <p class="eyebrow">
                            Proyección
                        </p>

                        <h2>
                            Evolución estimada del capital
                        </h2>
                    </div>

                    <div class="chart-legend">
                        <span>3 % Conservador</span>
                        <span>5 % Moderado</span>
                        <span>7 % Dinámico</span>
                    </div>
                </div>

                <div class="chart-container">
                    <svg
                        viewBox="0 0 ${width} ${height}"
                        role="img"
                        aria-label="Proyección del capital a lo largo del tiempo"
                    >
                        <line
                            x1="${padding.left}"
                            x2="${width - padding.right}"
                            y1="${y(plan.targetAmount)}"
                            y2="${y(plan.targetAmount)}"
                            class="target-line"
                        />

                        <text
                            x="${padding.left + 8}"
                            y="${y(plan.targetAmount) - 10}"
                            class="chart-label"
                        >
                            Objetivo
                        </text>

                        ${paths}

                        ${ticks.join('')}

                        <text
                            x="${width / 2}"
                            y="${height - 2}"
                            text-anchor="middle"
                            class="chart-label"
                        >
                            Años
                        </text>
                    </svg>
                </div>

                <p class="chart-note">
                    La proyección utiliza una rentabilidad anual
                    constante y aportaciones al final de cada mes.
                </p>

            </section>
        `;
    }
}

customElements.define(
    'projection-chart',
    ProjectionChart
);
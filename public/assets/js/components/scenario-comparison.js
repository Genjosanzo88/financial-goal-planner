class ScenarioComparison extends HTMLElement {
    connectedCallback() {
        document.addEventListener(
            'financial-plan-created',
            (event) => {
                this.render(event.detail);
            }
        );
    }

    formatCurrency(amount) {
        return new Intl.NumberFormat('es-ES', {
            style: 'currency',
            currency: 'EUR'
        }).format(amount);
    }

    formatDuration(totalMonths) {
        if (totalMonths === null) {
            return 'No alcanzable';
        }

        if (totalMonths === 0) {
            return 'Ya alcanzado';
        }

        const years = Math.floor(totalMonths / 12);
        const months = totalMonths % 12;

        const parts = [];

        if (years > 0) {
            parts.push(
                `${years} ${years === 1 ? 'año' : 'años'}`
            );
        }

        if (months > 0) {
            parts.push(
                `${months} ${months === 1 ? 'mes' : 'meses'}`
            );
        }

        return parts.join(' y ');
    }

    formatTimeDifference(months) {
        if (months === null) {
            return 'No alcanzable con estas condiciones';
        }

        if (months === 0) {
            return 'Justo en el horizonte previsto';
        }

        if (months > 0) {
            return `${this.formatDuration(months)} antes`;
        }

        return `${this.formatDuration(Math.abs(months))} después`;
    }

    render(plan) {
        const difference =
            plan.finalCapital - plan.targetAmount;

        const selectedHorizon =
            this.formatDuration(plan.years * 12);

        const realTime =
            this.formatDuration(plan.monthsToTarget);

        const timeDifference =
            this.formatTimeDifference(
                plan.timeDifferenceMonths
            );

        this.innerHTML = `
            <section class="card results">

                <div class="results-header">
                    <div>
                        <p class="eyebrow">Resultado</p>
                        <h2>${plan.goalName}</h2>
                        <p>${plan.clientName}</p>
                    </div>

                    <span class="status">
                        ${
                            plan.targetReached
                                ? 'Objetivo alcanzado'
                                : 'Objetivo no alcanzado'
                        }
                    </span>
                </div>

                <div class="primary-results">

                    <article>
                        <span>Objetivo</span>

                        <strong>
                            ${this.formatCurrency(
                                plan.targetAmount
                            )}
                        </strong>
                    </article>

                    <article>
                        <span>Capital proyectado</span>

                        <strong>
                            ${this.formatCurrency(
                                plan.finalCapital
                            )}
                        </strong>

                        <small>
                            ${
                                difference >= 0
                                    ? `+${this.formatCurrency(difference)} sobre el objetivo`
                                    : `${this.formatCurrency(Math.abs(difference))} por debajo`
                            }
                        </small>
                    </article>

                    <article>
                        <span>Horizonte elegido</span>

                        <strong>
                            ${selectedHorizon}
                        </strong>
                    </article>

                    <article>
                        <span>Tiempo para alcanzar el objetivo</span>

                        <strong>
                            ${realTime}
                        </strong>

                        <small>
                            ${timeDifference}
                        </small>
                    </article>

                </div>

                <div class="secondary-results">

                    <article>
                        <span>Total aportado</span>

                        <strong>
                            ${this.formatCurrency(
                                plan.totalContributed
                            )}
                        </strong>
                    </article>

                    <article>
                        <span>Rentabilidad estimada</span>

                        <strong>
                            ${this.formatCurrency(
                                plan.estimatedReturn
                            )}
                        </strong>
                    </article>

                    <article>
                        <span>Aportación mensual</span>

                        <strong>
                            ${this.formatCurrency(
                                plan.monthlyContribution
                            )}
                        </strong>
                    </article>

                    <article>
                        <span>Aportación necesaria</span>

                        <strong>
                            ${this.formatCurrency(
                                plan.requiredMonthlyContribution
                            )}
                        </strong>
                    </article>

                </div>

            </section>
        `;
    }
}

customElements.define(
    'scenario-comparison',
    ScenarioComparison
);
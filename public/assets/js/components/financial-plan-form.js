import {
    createFinancialPlan
} from '../api/financial-plan-api.js';

class FinancialPlanForm extends HTMLElement {
    connectedCallback() {
        this.render();
        this.bindEvents();
    }

    render() {
        this.innerHTML = `
            <section class="card">
                <h2>Tu objetivo financiero</h2>

                <form id="financial-plan-form">
                    <div class="form-grid">

                        <label>
                            Nombre del cliente

                            <input
                                name="clientName"
                                type="text"
                                value="Laura García"
                                required
                            >
                        </label>

                        <label>
                            Objetivo

                            <input
                                name="goalName"
                                type="text"
                                value="Comprar vivienda"
                                required
                            >
                        </label>

                        <label>
                            Objetivo económico (€)

                            <input
                                name="targetAmount"
                                type="number"
                                min="0"
                                step="0.01"
                                value="100000"
                                required
                            >
                        </label>

                        <label>
                            Capital inicial (€)

                            <input
                                name="initialCapital"
                                type="number"
                                min="0"
                                step="0.01"
                                value="12000"
                                required
                            >
                        </label>

                        <label>
                            Aportación mensual (€)

                            <input
                                name="monthlyContribution"
                                type="number"
                                min="0"
                                step="0.01"
                                value="300"
                                required
                            >
                        </label>

                        <label>
                            Horizonte (años)

                            <input
                                name="years"
                                type="number"
                                min="1"
                                step="1"
                                value="15"
                                required
                            >
                        </label>

                        <label>
                            Rentabilidad anual estimada (%)

                            <input
                                name="annualRate"
                                type="number"
                                min="0"
                                step="0.1"
                                value="5"
                                required
                            >
                        </label>

                    </div>

                    <button type="submit">
                        Calcular plan
                    </button>

                    <p
                        class="form-error"
                        role="alert"
                        hidden
                    ></p>
                </form>
            </section>
        `;
    }

    bindEvents() {
        const form = this.querySelector(
            '#financial-plan-form'
        );

        const errorElement = this.querySelector(
            '.form-error'
        );

        const button = form.querySelector(
            'button[type="submit"]'
        );

        form.addEventListener(
            'submit',
            async (event) => {
                event.preventDefault();

                errorElement.hidden = true;
                errorElement.textContent = '';

                const originalButtonText =
                    button.textContent;

                button.disabled = true;
                button.textContent = 'Calculando...';

                form.setAttribute(
                    'aria-busy',
                    'true'
                );

                const formData = new FormData(form);

                const data = {
                    clientName:
                        formData.get('clientName'),

                    goalName:
                        formData.get('goalName'),

                    targetAmount:
                        Number(
                            formData.get(
                                'targetAmount'
                            )
                        ),

                    initialCapital:
                        Number(
                            formData.get(
                                'initialCapital'
                            )
                        ),

                    monthlyContribution:
                        Number(
                            formData.get(
                                'monthlyContribution'
                            )
                        ),

                    years:
                        Number(
                            formData.get('years')
                        ),

                    annualRate:
                        Number(
                            formData.get(
                                'annualRate'
                            )
                        )
                };

                try {
                    const result =
                        await createFinancialPlan(
                            data
                        );

                    this.dispatchEvent(
                        new CustomEvent(
                            'financial-plan-created',
                            {
                                detail: result,
                                bubbles: true
                            }
                        )
                    );
                } catch (error) {
                    errorElement.textContent =
                        error instanceof Error
                            ? error.message
                            : 'Ha ocurrido un error inesperado.';

                    errorElement.hidden = false;
                } finally {
                    button.disabled = false;

                    button.textContent =
                        originalButtonText;

                    form.removeAttribute(
                        'aria-busy'
                    );
                }
            }
        );
    }
}

customElements.define(
    'financial-plan-form',
    FinancialPlanForm
);
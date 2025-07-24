document.addEventListener('DOMContentLoaded', function() {
    const variantsSection = document.getElementById('variants-section');
    const variantContainer = document.getElementById('variant-container');
    const generateButton = document.getElementById('generate-variants');

    generateButton.addEventListener('click', function() {
        const attributes = document.querySelectorAll('.attribute-group');
        const variants = [];

        attributes.forEach(attribute => {
            const attributeName = attribute.querySelector('input[name="attribute_name"]').value;
            const attributeValue = attribute.querySelector('input[name="attribute_value"]').value;
            variants.push({
                attribute_name: attributeName,
                attribute_value: attributeValue
            });
        });

        console.log(variants);
    });
});


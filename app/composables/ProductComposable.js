export const useProductList = productData => {
    const products = ref({...productData})
    const newProduct = reactive({
        name: null,
        slug: null,
        description: null,
        price: 0,
        stock: 0,
        new_image: null
    })

    const selectedProduct = ref({})

    const selectImage = e => selectedProduct.value.new_image = e.target.files[0]

    const selectProduct = p => selectedProduct.value = {...p}

    const removeProductFromList = productId => {
        const index = products.value.data.findIndex(item => item.id === productId)
        if (index > -1) {
            products.value.data.splice(index, 1)
        }
    }

    const save = () => {
        const isCreate = !selectedProduct.value?.id

        const formData = new FormData()
        if (!isCreate) {
            formData.append('id', selectedProduct.value.id)
        }
        formData.append('name', selectedProduct.value.name)
        formData.append('description', selectedProduct.value.description)
        formData.append('price', selectedProduct.value.price)
        formData.append('stock', selectedProduct.value.stock)
        if (selectedProduct.value?.new_image) {
            formData.append('new_image', selectedProduct.value.new_image)
        }

        let postUrl = 'products'

        if (!isCreate) {
            postUrl += '/' + selectedProduct.value.id
        }

        useAxios()
            .post(postUrl, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(res => {
                if (res.data.success) {
                    if (isCreate) {
                        products.value.data.push(res.data.product)
                    } else {
                        const index = products.value.data.findIndex(item => item.id === res.data.product.id)
                        if (index > -1) {
                            products.value.data[index] = res.data.product
                        }
                    }
                }
            })
            .catch(err => console.log(err))
            .finally(() => useModal('product_save_modal', false))
    }

    const removeProduct = productId => {
        useSwal.fire({
            title: `Bu məhsulu silmək istədiyinizə əminsiniz ?`,
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: 'Yes',
            denyButtonText: 'No',
            icon: 'warning'
        }).then(result => {
            if (result.isConfirmed) {
                useAxios()
                    .delete(`products/` + productId)
                    .then(res => {
                        if (res.data.success) {
                            alert('Məhsul silindi')
                            removeProductFromList(productId)
                        }
                    })
                    .catch(err => console.log(err))
            }
        })
    }

    return {
        products,
        newProduct,
        selectedProduct,
        removeProduct,
        selectImage,
        selectProduct,
        save,
    }
}
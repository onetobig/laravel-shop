const addressData = require('china-area-data/v4/data');

import _ from 'lodash';

Vue.component('select-district', {
    // 组件属性
    props: {
        // 初始化的值
        initValue: {
            type: Array, // 格式是数组
            default: () => ([]), // 默认是个空数组
        }
    },
    // 组件内的数据
    data() {
        return {
            provinces: addressData['86'], // 省列表
            cities: {},
            districts: {},
            provinceId: '',
            cityId: '',
            districtId: '',
        };
    },
    watch: {
        provinceId(newVal) {
            if (!newVal) {
                this.cities = {};
                this.cityId = '';
                return;
            }

            this.cities = addressData[newVal];
            if (!this.cities[this.cityId]) {
                this.cityId = '';
            }
        },
        cityId(newVal) {
            if (!newVal) {
                this.districts = {};
                this.districtId = '';
                return;
            }

            this.districts = addressData[newVal];
            if (!this.districts[this.districtId]) {
                this.districtId = '';
            }
        },
        districtId() {
            // 触发一个名为 change 的 Vue 事件，事件的值就是当前选中的省市区名称，格式为数组
            this.$emit('change', [this.provinces[this.provinceId], this.cities[this.cityId], this.districts[this.districtId]]);
        },
    },
    created() {
        this.setFromValue(this.initValue);
    },
    methods: {
        setFromValue(value) {
            // 过滤空值
            value = _.filter(value);
            if (value.length === 0) {
                this.provinceId = '';
                return;
            }

            const provinceId = _.findKey(this.provinces, o => o === value[0]);
            if (!provinceId) {
                this.provinceId = '';
                return;
            }
            this.provinceId = provinceId;

            const cityId = _.findKey(addressData[provinceId], o => o === value[1]);
            // 没找到，清空城市的值
            if (!cityId) {
                this.cityId = '';
                return;
            }
            // 找到了，将当前城市设置成对应的ID
            this.cityId = cityId;
            // 由于观察器的作用，这个时候地区列表已经变成了对应城市的地区列表
            // 从当前地区列表找到与数组第三个元素同名的项的索引
            const districtId = _.findKey(addressData[cityId], o => o === value[2]);
            // 没找到，清空地区的值
            if (!districtId) {
                this.districtId = '';
                return;
            }
            // 找到了，将当前地区设置成对应的ID
            this.districtId = districtId;
        }
    }
});
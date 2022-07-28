/**
 * @author Junaid Ahmed
 * @created_at 2018-April
 * @param element
 * @param url
 * @param data
 */
 const ArrayHelpers = function () {

  let self        = this;

  this.in_array = function( value, exists ){
    return !($.inArray( value, exists ) == -1);
  },

  /**
   * Returns an array with arrays of the given size.
   *
   * @param myArray {Array} array to split
   * @param chunk_size {Integer} Size of every group
   */
  this.array_chunk = function( items, chunk_size)
  {
    var index       = 0;
    var arrayLength =  items.length;
    var tempArray   = [];
    
    for (index = 0; index < arrayLength; index += chunk_size)
    {
      myChunk =  items.slice(index, index+chunk_size);      
      tempArray.push(myChunk);
    }

    return tempArray;
  },

  this.serialize_convert_array = function( formdata ){

    let _tmp  = {};

    $.each(formdata, function( index, value ){

      _tmp[value.name] = value.value;
    });

    return _tmp;
  }
}

